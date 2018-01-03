<?php

namespace BudgetBundle\Listener;

use BudgetBundle\Entity\BudgetExpense;
use BudgetBundle\Entity\BudgetExpenseSanction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Transition;

class BudgetExpenseApprovedSubscriber implements EventSubscriberInterface
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
            'workflow.budget_expense.entered.approved' => array(
                array('createBillSanctionRegister', 10)
            )
        );
    }

    public function createBillSanctionRegister(Event $event)
    {
        if (!$event->getSubject() instanceof BudgetExpense) {
            return;
        }

        $budgeExpenseSanction = new BudgetExpenseSanction();
        $budgeExpenseSanction->setBudgetExpense($event->getSubject());
        $budgeExpenseSanction->setOffice($event->getSubject()->getOffice());
        $budgeExpenseSanction->setTotalAmount($event->getSubject()->getAmount());
        $this->em->persist($budgeExpenseSanction);
        $this->em->flush();

        $workflow = $this->workflow->get($budgeExpenseSanction, 'expense_sanction');

        $workflow->apply($budgeExpenseSanction, 'init_draft');
    }
}
