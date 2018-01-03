<?php

namespace BudgetBundle\Listener;

use BudgetBundle\Entity\Budget;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OfficeBudgetWorkflowSubscriber implements EventSubscriberInterface
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
                array('renderView', 10)
            )
        );
    }

    public function renderView(GetResponseWorkflowEvent $event)
    {
        /** @var Budget $budget */
        $budget = $event->getEntity();
        if(!$budget instanceof Budget) {
            return;
        }

        $office = $budget->getOffice();
        $budgetDetailRepo = $this->em->getRepository('BudgetBundle:BudgetDetail');
        $budgetYear = $budget->getFinancialYear()->getId();

        $builder = new ResponseBuilderData('@Budget/BudgetRequest/view.html.twig', [
            'budgetHead'           => $this->em->getRepository('BudgetBundle:BudgetHead')->getParentBudgetHead(),
            'budget'               => $budget,
            'budgetYear'           => $budgetYear,
            'budgetAmount'         => $budgetDetailRepo->getBudgetAmountByYear($budgetYear, $office),
            'currentBudgetAmount'  => $budgetDetailRepo->getBudgetAmountByYear($budgetYear - 1, $office),
            'previousBudgetAmount' => $budgetDetailRepo->getBudgetAmountByYear($budgetYear - 2, $office),
        ]);

        $event->setResponseBuilder($builder);
    }
}
