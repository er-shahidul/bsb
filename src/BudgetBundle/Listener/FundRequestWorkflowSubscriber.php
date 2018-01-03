<?php

namespace BudgetBundle\Listener;

use BudgetBundle\Entity\Budget;
use BudgetBundle\Entity\FundRequest;
use BudgetBundle\Manager\BudgetManager;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FundRequestWorkflowSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var BudgetManager */
    protected $budgetManager;

    public function __construct(EntityManagerInterface $entityManager, BudgetManager $budgetManager)
    {
        $this->em = $entityManager;
        $this->budgetManager = $budgetManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.view.event' => array(
                array('renderView', 10)
            )
        );
    }

    public function renderView(GetResponseWorkflowEvent $event)
    {
        /** @var Budget $budget */
        $budget = $event->getEntity();
        if(!$budget instanceof FundRequest) {
            return;
        }

        $this->budgetManager->setBudgetStats($budget) ;
        $showAmountField = strpos($budget->getStatus(), 'approval');

        $view = $showAmountField === false ? '@Budget/FundRequest/view.html.twig' : '@Budget/FundRequest/allocation-view.html.twig';

        $builder = new ResponseBuilderData(
            $view,
            [
                'budget'     => $budget,
                'budgetYear' => $budget->getFinancialYear()->getId(),
                'showAmountField' => $showAmountField
            ]
        );

        $event->setResponseBuilder($builder);
    }
}
