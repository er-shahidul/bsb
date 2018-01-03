<?php

namespace BudgetBundle\Tests\Manager\BudgetManager;

use AppBundle\Utility\DateUtil;
use BudgetBundle\Manager\BudgetManager;
use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class BudgetManagerTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $entityManager;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $policyManager;

    /** @var  BudgetManager */
    private $budgetManager;

    public function setUp()
    {
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->policyManager = $this->createMock(PolicyManager::class);
        $this->budgetManager = new BudgetManager($this->entityManager, $this->policyManager);
    }

    public function testDasbCannotCreateBudgetIfLastYearAmendmentNotCompleted()
    {
        $this->checkDasbCanNotCreateBudget(null, null);
    }

    public function testDasbCannotCreateBudgetIfLastYearAmendmentDoneButBudgetSubmissionDateExpired()
    {
        $this->checkDasbCanNotCreateBudget(true, new \DateTime('yesterday'));
    }

    public function testDasbCannotCreateBudgetIfLastYearAmendmentDoneButBudgetSubmissionDateEmpty()
    {
        $this->checkDasbCanNotCreateBudget(true, null);
    }

    public function testDasbCanCreateBudgetIfAmendmentDoneAndBudgetSubmissionDateIsNotExpired() {
        $this->mockPolicyManagerCall(true, new \DateTime('tomorrow'));
        $this->assertTrue($this->budgetManager->canDASBCreateBudget());
    }

    protected function checkDasbCanNotCreateBudget($amendmentDone, $submissionDate) {
        $this->mockPolicyManagerCall($amendmentDone, $submissionDate);
        $this->assertFalse($this->budgetManager->canDASBCreateBudget());
    }

    protected function mockPolicyManagerCall($amendmentDone, $submissionDate)
    {
        $year = DateUtil::getCurrentFinancialYear();
        $map = [
            ['app.fiscalyear.' . $year . '.amendment_completed', null, $amendmentDone],
            ['budget.last_date_of_budget_submission', Type::DATE, $submissionDate]
        ];

        $this->policyManager
            ->method('getPolicyValue')
            ->will($this->returnValueMap($map));
    }
}
