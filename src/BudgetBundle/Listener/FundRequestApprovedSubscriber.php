<?php

namespace BudgetBundle\Listener;

use AppBundle\Entity\FinancialYear;
use BudgetBundle\Entity\Budget;
use BudgetBundle\Entity\BudgetDetail;
use BudgetBundle\Entity\BudgetHead;
use BudgetBundle\Entity\BudgetSummary;
use BudgetBundle\Entity\BudgetSummaryDetail;
use BudgetBundle\Entity\FundRequest;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Transition;

class FundRequestApprovedSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var Registry */
    protected $workflow;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager, Registry $registry, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $entityManager;
        $this->workflow = $registry;
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.fund_request.entered.approved' => array(
                array('update', 10),
            ),
        );
    }

    public function update(Event $event)
    {
        /** @var FundRequest $budgeSummary */
        $budget = $event->getSubject();

        if (!$budget instanceof FundRequest) {
            return;
        }

        $this->updateRemainingAmount($budget);

    }

    /**
     * @param BudgetHead $budgetHead
     * @param FinancialYear $financialYear
     * @return \BudgetBundle\Entity\BudgetSummaryDetail|null|object
     */
    protected function getBudgetSummaryDetail(BudgetHead $budgetHead, FinancialYear $financialYear)
    {
        $budgetSummary = $this->em->getRepository('BudgetBundle:BudgetSummary')->findOneBy(['financialYear' => $financialYear]);
        return $this->em->getRepository('BudgetBundle:BudgetSummaryDetail')->findOneBy(['budgetSummary' => $budgetSummary, 'budgetHead' => $budgetHead]);
    }

    protected function updateRemainingAmount(FundRequest $fundRequest)
    {
        /** @var BudgetDetail $budgetDetail */
        foreach ($fundRequest->getBudgetDetails() as $budgetDetail) {
            $bsd = $this->getBudgetSummaryDetail($budgetDetail->getBudgetHead(), $fundRequest->getFinancialYear());
            $bsd->setRemainingAmount($bsd->getRemainingAmount() - $budgetDetail->getAmount());
        }

        $this->em->flush();
    }
}