<?php

namespace BudgetBundle\Listener;

use BudgetBundle\Entity\Budget;
use BudgetBundle\Entity\BudgetExpense;
use BudgetBundle\Entity\BudgetExpenseSanction;
use BudgetBundle\Entity\BudgetIncome;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BudgetIncomeWorkflowSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.view.event' => array(
                array('incomeView', 10),
            ),
        );
    }

    public function incomeView(GetResponseWorkflowEvent $event)
    {
        if (!$event->getEntity() instanceof BudgetIncome) {
            return;
        }

        $builder = new ResponseBuilderData('@Budget/BudgetIncome/view.html.twig',
            ['budgetIncome' => $event->getEntity()]
        );

        $event->setResponseBuilder($builder);
    }
}