<?php

namespace BudgetBundle\Listener;

use BudgetBundle\Entity\BudgetSummary;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class BudgetCompilationWorkflowSubscriber implements EventSubscriberInterface
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
            ),
            'workflow.budget_compilation.entered' => array(
                array('syncStatus', 10)
            )
        );
    }

    public function renderView(GetResponseWorkflowEvent $event)
    {
        if(!$event->getEntity() instanceof BudgetSummary) {
            return;
        }

        /** @var BudgetSummary $budgetSummary */
        $budgetSummary = $event->getEntity();
        $budgetYear = $budgetSummary->getFinancialYear()->getId();

        switch ($this->getEntityStatusPrefix($budgetSummary->getStatus())) {
            case 'allocation':
                $event->setResponseBuilder($this->getBudgetAllocationView($budgetSummary, $budgetYear));
                break;
            case 'distribution':
                $event->setResponseBuilder($this->getBudgetDistributionView($budgetSummary, $budgetYear));
                break;
            case 'amendmentrequest':
                $event->setResponseBuilder($this->getAmendmentRequestView($budgetSummary));
                break;
            case 'amendmentsanction':
                $event->setResponseBuilder($this->getAmendmentSanctionView($budgetSummary));
                break;
            default:
            $event->setResponseBuilder($this->getBudgetCompilationView($budgetSummary, $budgetYear));
        }
    }

    protected function getBudgetCompilationView($budgetSummary, $budgetYear)
    {
        return new ResponseBuilderData('@Budget/BudgetCompilation/view.html.twig',
            $this->getBudgetCompilationAndDistributionData(
                $budgetSummary,
                $budgetYear
            )
        );

    }

    protected function getBudgetAllocationView($budgetSummary, $budgetYear)
    {
        return new ResponseBuilderData(
            '@Budget/BudgetAllocation/view.html.twig',
            [
                'budgetAmount'  => $this->em->getRepository(
                    'BudgetBundle:BudgetSummaryDetail'
                )->getBudgetSummaryAmountByYear($budgetYear),
                'budgetSummary' => $budgetSummary,
                'budgetYear'    => $budgetYear,
                'budgetHead'    => $this->em->getRepository('BudgetBundle:BudgetHead')->getParentBudgetHead(),
            ]
        );
    }

    protected function getBudgetDistributionView($budgetSummary, $budgetYear)
    {
        return new ResponseBuilderData(
            '@Budget/BudgetDistribution/view.html.twig',
            $this->getBudgetCompilationAndDistributionData($budgetSummary, $budgetYear)
        );
    }

    protected function getAmendmentRequestView($budgetSummary)
    {
        return new ResponseBuilderData(
            '@Budget/BudgetAmendment/view.html.twig',
            $this->getBudgetAmendmentData($budgetSummary)
        );
    }

    protected function getAmendmentSanctionView($budgetSummary)
    {
        return new ResponseBuilderData(
            '@Budget/BudgetAmendment/view-sanction.html.twig',
            $this->getBudgetAmendmentData($budgetSummary)
        );
    }

    protected function getEntityStatusPrefix($status)
    {
        return explode('_', $status)[0];
    }

    /**
     * @param $budgetSummary
     * @param $budgetYear
     * @return array
     */
    protected function getBudgetCompilationAndDistributionData(
        $budgetSummary,
        $budgetYear
    ) {
        $budgetDetailRepo = $this->em->getRepository('BudgetBundle:BudgetDetail');
        $budgetSummaryDetailRepo = $this->em->getRepository('BudgetBundle:BudgetSummaryDetail');
        return [
            'budgetHead'           => $this->em->getRepository('BudgetBundle:BudgetHead')->getParentBudgetHead(),
            'budgetSummary'        => $budgetSummary,
            'budgetYear'           => $budgetYear,
            'budgetAmount'         => $budgetSummaryDetailRepo->getBudgetSummaryAmount($budgetSummary),
            'currentBudgetAmount'  => $budgetSummaryDetailRepo->getBudgetSummaryAmountByYear($budgetYear - 1),
            'previousBudgetAmount' => $budgetSummaryDetailRepo->getBudgetSummaryAmountByYear($budgetYear - 2),
            'dasbBudgetData'       => $budgetDetailRepo->getAllBudgetAmountByYear($budgetYear),
            'offices'              => $this->em->getRepository('AppBundle:Office')->findAll(),
        ];
    }

    protected function getBudgetAmendmentData($budgetSummary)
    {
        return [
            'budgetSummary' => $budgetSummary,
            'budgetAmount'  => $this->em->getRepository('BudgetBundle:BudgetSummaryDetail')->getBudgetSummaryAmountWithTotal($budgetSummary),
            'budgetHead'    => $this->em->getRepository('BudgetBundle:BudgetHead')->getParentBudgetHead(),
        ];
    }

    public function syncStatus(Event $event)
    {
        $budgetSummary = $event->getSubject();
        if (!$budgetSummary instanceof BudgetSummary) {
            return;
        }

        if ($budgetSummary->isAmendmentStarted()) {
            $budgetSummary->setAmendmentStatus($budgetSummary->getStatus());
        } else {
            $budgetSummary->setBudgetStatus($budgetSummary->getStatus());
        }

        $this->em->flush();
    }
}
