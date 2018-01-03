<?php

namespace BudgetBundle\Listener;

use BudgetBundle\Entity\PreBudgetIncomeSummary;
use BudgetBundle\Entity\PreBudgetIncomeSummaryDetail;
use BudgetBundle\Entity\BudgetIncomeSummary;
use BudgetBundle\Entity\BudgetIncomeSummaryDetail;
use BudgetBundle\Manager\BudgetManager;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class PreBudgetIncomeSummaryWorkflowSubscriber implements EventSubscriberInterface
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
            )
        );
    }

    public function incomeSummaryView(GetResponseWorkflowEvent $event)
    {
        /** @var PreBudgetIncomeSummary $budget */
        $budget = $event->getEntity();
        if(!$budget instanceof PreBudgetIncomeSummary) {
            return;
        }

        $builder = new ResponseBuilderData(
            '@Budget/PreBudgetIncome/view.html.twig',
            $this->em->getRepository('BudgetBundle:PreBudgetIncomeSummary')->getPreBudgetSummaryViewUpdateData($budget)
        );
        $event->setResponseBuilder($builder);
    }
}
