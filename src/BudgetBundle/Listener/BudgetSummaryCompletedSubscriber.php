<?php

namespace BudgetBundle\Listener;

use BudgetBundle\Entity\Budget;
use BudgetBundle\Entity\BudgetSummary;
use BudgetBundle\Entity\BudgetSummaryDetail;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Transition;

class BudgetSummaryCompletedSubscriber implements EventSubscriberInterface
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
            'workflow.budget_compilation.entered.completed' => array(
                array('updateBudget', 10),
                array('updateRemaining', 10),
            ),
        );
    }

    public function updateBudget(Event $event)
    {
        /** @var BudgetSummary $budgeSummary */
        $budgeSummary = $event->getSubject();

        if (!$budgeSummary instanceof BudgetSummary || $budgeSummary->isAmendmentStarted()) {
            return;
        }

        $budgetEntities = $this->getBudgetEntities($budgeSummary->getFinancialYear());

        foreach ($budgetEntities as $budget) {
            $officeType = $budget->getOffice()->getOfficeType();
            if ($officeType->getName() == 'HQ') {
                $budget->setStatus('approved');
                $workflow = $this->workflow->get($budget, 'office_budget');
                $transition = new Transition(
                    'hidden_transaction',
                    'wait_for_budget_compilation',
                    'approved'
                );
            } else {
                $budget->setStatus('wait_for_secretary_approval');
                $workflow = $this->workflow->get($budget, 'office_budget');
                $transition = new Transition(
                    'hidden_transaction',
                    'wait_for_budget_compilation',
                    'wait_for_secretary_approval'
                );
            }
            $this->eventDispatcher->dispatch('workflow.entered',
                new Event($budget, $workflow->getMarking($budget), $transition, 'office_budget')
            );
        }
    }

    protected function getBudgetEntities($year)
    {
        return $this->em->getRepository('BudgetBundle:Budget')->findBy(['financialYear' => $year]);
    }

    public function updateRemaining(Event $event)
    {
        /** @var BudgetSummary $budgeSummary */
        $budgeSummary = $event->getSubject();

        if (!$budgeSummary instanceof BudgetSummary || !$budgeSummary->isAmendmentStarted()) {
            return;
        }

        /** @var BudgetSummaryDetail $bsd */
        foreach ($budgeSummary->getBudgetSummaryDetails() as $bsd) {
            $bsd->setAmount($bsd->getAmendmentSanctionAmount());
            $distributeAmount = $bsd->getSanctionAmount() - $bsd->getRemainingAmount();
            $bsd->setRemainingAmount($bsd->getAmount() - $distributeAmount);
        }

        $this->em->flush();
    }
}
