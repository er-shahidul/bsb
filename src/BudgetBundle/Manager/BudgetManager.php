<?php

namespace BudgetBundle\Manager;

use AppBundle\Entity\FinancialYear;
use AppBundle\Entity\Office;
use AppBundle\Utility\DateUtil;
use BudgetBundle\Entity\BaseBudget;
use BudgetBundle\Entity\BudgetHead;
use BudgetBundle\Entity\BudgetSummary;
use BudgetBundle\Entity\BudgetIncomeSummary;
use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;

class BudgetManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var PolicyManager
     */
    protected $policyManager;

    public function __construct(EntityManagerInterface $entityManager, PolicyManager $policyManager)
    {
        $this->em = $entityManager;
        $this->policyManager = $policyManager;
    }

    public function canDASBCreateBudget()
    {
        $lastSubmissionDate = $this->policyManager->getPolicyValue('budget.last_date_of_budget_submission', Type::DATE);

        if(empty($lastSubmissionDate)) {
            return false;
        }

        $lastSubmissionDate->modify(date('Y') . $lastSubmissionDate->format('-m-d') . '23:59:59');

        return $lastSubmissionDate > new \DateTime();
    }

    /**
     * @param BudgetSummary $budgetSummary
     * @return bool
     */
    public function canCreateAmendment($budgetSummary)
    {
        if (!$budgetSummary || $budgetSummary->getBudgetStatus() != 'completed' || $budgetSummary->isAmendmentStarted()) {
            return false;
        }

        $budgetPolicies = $this->policyManager->getGroupPolicy('budget');
        $amendment = isset($budgetPolicies['last_date_of_amendment_submission']) ? new \DateTime($budgetPolicies['last_date_of_amendment_submission']) : false;
        $amendment->modify(date('Y') . $amendment->format('-m-d') . '23:59:59');

        return $amendment > new \DateTime();
    }

    public function isPreBudgetSummaryApprovedForNextYear()
    {
        return $this->em->getRepository('BudgetBundle:PreBudgetSummary')->findOneBy([
            'financialYear' => new FinancialYear(DateUtil::getNextFinancialYear()),
            'status' => 'approved'
        ]);
    }

    public function isBudgetSummaryExistForNextFinancialYear()
    {
        return $this->em->getRepository('BudgetBundle:BudgetSummary')->getBudgetSummaryByYear(
            DateUtil::getNextFinancialYear()
        );
    }

    public function isPreBudgetSummaryExistForNextFinancialYear()
    {
        return $this->em->getRepository('BudgetBundle:PreBudgetSummary')->getPreBudgetSummaryByYear(
            DateUtil::getNextFinancialYear()
        );
    }

    public function getOfficesWhoRequestedBudgetForNextFinancialYear()
    {
        $offices = $this->em->getRepository('AppBundle:Office')->getWhichBudgetRequestIsNotAwaitingForBudgetCompilation(
            DateUtil::getNextFinancialYear()
        );

        return $offices;
    }


    public function setBudgetStats(BaseBudget $budget)
    {
        foreach ($budget->getBudgetDetails() as $budgetDetail) {
            $budgetDetail->stats = $this->getBudgetHeadStats($budgetDetail->getBudgetHead(), $budget->getFinancialYear()->getId(), $budget->getOffice()) ;
            $budgetDetail->stats['headRemaining'] = $this->em->getRepository('BudgetBundle:BudgetSummaryDetail')->getRemainingAmountOfHead($budgetDetail->getBudgetHead(), $budget->getFinancialYear());
        }
    }

    public function getBudgetHeadStats(BudgetHead $budgetHead, $currentFinancialYear, Office $office)
    {
        $data = [
            'current' => $this->em->getRepository('BudgetBundle:Budget')->getHeadTotal($budgetHead, $currentFinancialYear, $office),
            'revise' => $this->em->getRepository('BudgetBundle:FundRequest')->getHeadTotal($budgetHead, $currentFinancialYear, $office),
            //'prev' => $this->em->getRepository('BudgetBundle:BaseBudget')->getHeadTotal($budgetHead, $currentFinancialYear - 1, $office),
            //'beforePrev' => $this->em->getRepository('BudgetBundle:BaseBudget')->getHeadTotal($budgetHead, $currentFinancialYear - 2, $office),
            'expense' => number_format($this->em->getRepository('BudgetBundle:BudgetExpenseSanction')->getHeadTotal($budgetHead, $currentFinancialYear, $office) / 1000, 2),
            'headTitle' => $budgetHead->getTitleEn(),
            'headCode' => $budgetHead->getCode(),
        ];

        return $data;
    }

    public function getBudgetSummaryData(BudgetSummary $budgetSummary)
    {
        return [
            'budgetSummary' => $budgetSummary,
            'budgetAmount'  => $this->em->getRepository('BudgetBundle:BudgetSummaryDetail')->getBudgetSummaryAmountWithTotal($budgetSummary),
            'budgetHead'    => $this->em->getRepository('BudgetBundle:BudgetHead')->getParentBudgetHead(),
        ];
    }

    public function getBudgetIncomeSummaryData(BudgetIncomeSummary $budgetSummary)
    {
        return [
            'budgetSummary' => $budgetSummary,
            'budgetAmount'  => $this->em->getRepository('BudgetBundle:BudgetIncomeSummaryDetail')->getBudgetSummaryAmountWithTotal($budgetSummary),
            'budgetHead'    => $this->em->getRepository('BudgetBundle:BudgetIncomeHead')->getParentBudgetHead(),
        ];
    }

    public function isPreBudgetIncomeSummaryExistForNextFinancialYear()
    {
        return $this->em->getRepository('BudgetBundle:PreBudgetIncomeSummary')->getPreBudgetSummaryByYear(
            DateUtil::getNextFinancialYear()
        );
    }

    public function isBudgetIncomeSummaryExistForNextFinancialYear()
    {
        return $this->em->getRepository('BudgetBundle:BudgetIncomeSummary')->getBudgetSummaryByYear(
            DateUtil::getNextFinancialYear()
        );
    }
}
