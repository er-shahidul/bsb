<?php

namespace BudgetBundle\Listener;

use BudgetBundle\Entity\PreBudgetSummary;
use BudgetBundle\Manager\BudgetManager;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PreBudgetSummaryWorkflowSubscriber implements EventSubscriberInterface
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
                array('renderView', 10),
            )
        );
    }

    public function renderView(GetResponseWorkflowEvent $event)
    {
        /** @var PreBudgetSummary $budget */
        $budget = $event->getEntity();
        if(!$budget instanceof PreBudgetSummary) {
            return;
        }

        $builder = new ResponseBuilderData(
            '@Budget/PreBudget/view.html.twig',
            $this->em->getRepository('BudgetBundle:PreBudgetSummary')->getPreBudgetSummaryViewUpdateData($budget)
        );
        $event->setResponseBuilder($builder);
    }
}
