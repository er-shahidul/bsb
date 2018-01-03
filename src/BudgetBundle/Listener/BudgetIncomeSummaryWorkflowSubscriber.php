<?php

namespace BudgetBundle\Listener;

use BudgetBundle\Entity\BudgetIncomeSummary;
use BudgetBundle\Entity\BudgetIncomeSummaryDetail;
use BudgetBundle\Manager\BudgetManager;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class BudgetIncomeSummaryWorkflowSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var  BudgetManager */
    protected $budgetManager;

    public function __construct(EntityManagerInterface $entityManager, BudgetManager $manager)
    {
        $this->em = $entityManager;
        $this->budgetManager = $manager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.view.event' => array(
                array('incomeSummaryView', 10),
            ),
            'workflow.budget_income_summary.entered' => array(
                array('syncStatus', 10),
            ),
            'workflow.budget_income_summary.entered.completed' => array(
                array('updateAmount', 10),
            ),
        );
    }

    public function incomeSummaryView(GetResponseWorkflowEvent $event)
    {
        /** @var BudgetIncomeSummary $budget */
        $budget = $event->getEntity();
        if(!$budget instanceof BudgetIncomeSummary) {
            return;
        }

        $status = explode('_', $budget->getStatus())[0];
        switch ($status) {
            case 'amendment':
                $event->setResponseBuilder($this->getIncomeSummaryAmendmentView($budget));
                break;
            default:
                $event->setResponseBuilder($this->getIncomeSummaryView($budget));
        }
    }

    public function getIncomeSummaryView($budget)
    {
        return new ResponseBuilderData(
            '@Budget/BudgetIncomeSummary/view.html.twig',
            $this->em->getRepository('BudgetBundle:BudgetIncomeSummary')->getBudgetSummaryViewUpdateData($budget)
        );
    }

    public function getIncomeSummaryAmendmentView($budget)
    {
        return new ResponseBuilderData(
            '@Budget/BudgetIncomeSummaryAmendment/view.html.twig',
            $this->budgetManager->getBudgetIncomeSummaryData($budget)
        );
    }

    public function syncStatus(Event $event)
    {
        $budgetSummary = $event->getSubject();
        if (!$budgetSummary instanceof BudgetIncomeSummary) {
            return;
        }

        if ($budgetSummary->isAmendmentStarted()) {
            $budgetSummary->setAmendmentStatus($budgetSummary->getStatus());
        } else {
            $budgetSummary->setBudgetStatus($budgetSummary->getStatus());
        }

        $this->em->flush();
    }

    public function updateAmount(Event $event)
    {
        $budgetSummary = $event->getSubject();
        if (!$budgetSummary instanceof BudgetIncomeSummary) {
            return;
        }

        /** @var BudgetIncomeSummaryDetail $d */
        foreach ($budgetSummary->getBudgetSummaryDetails() as $d) {
            $amount = $budgetSummary->isAmendmentStarted() ? $d->getAmendmentRequestAmount() : $d->getRequestAmount();

            $d->setAmount($amount);
        }
        $this->em->flush();
    }
}
