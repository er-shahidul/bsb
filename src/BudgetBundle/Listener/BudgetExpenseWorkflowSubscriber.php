<?php

namespace BudgetBundle\Listener;

use BudgetBundle\Entity\Budget;
use BudgetBundle\Entity\BudgetExpense;
use BudgetBundle\Entity\BudgetExpenseSanction;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BudgetExpenseWorkflowSubscriber implements EventSubscriberInterface
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
                array('expenseView', 10),
                array('expenseSanctionView', 10),
            ),
        );
    }

    public function expenseView(GetResponseWorkflowEvent $event)
    {
        if (!$event->getEntity() instanceof BudgetExpense) {
            return;
        }

        $builder = new ResponseBuilderData('@Budget/BudgetExpense/view.html.twig',
            ['budgetExpense' => $event->getEntity()]
        );

        $event->setResponseBuilder($builder);
    }

    public function expenseSanctionView(GetResponseWorkflowEvent $event)
    {
        if (!$event->getEntity() instanceof BudgetExpenseSanction) {
            return;
        }

        /** @var BudgetExpenseSanction $budgetExpenseSanction */
        $budgetExpenseSanction = $event->getEntity();
        $builder = new ResponseBuilderData(
            '@Budget/BudgetExpenseSanction/view.html.twig', [
                'budgetExpenseSanction' => $budgetExpenseSanction,
                'budgetExpense'         => $budgetExpenseSanction->getBudgetExpense(),
                'validForm' => $budgetExpenseSanction->getChequeLipiDate() && $budgetExpenseSanction->getChequeLipiNo()
            ]
        );

        $event->setResponseBuilder($builder);
    }
}