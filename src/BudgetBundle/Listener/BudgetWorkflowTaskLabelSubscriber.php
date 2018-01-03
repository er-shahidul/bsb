<?php

namespace BudgetBundle\Listener;

use BudgetBundle\Entity\BudgetExpense;
use BudgetBundle\Entity\BudgetExpenseSanction;
use BudgetBundle\Entity\BudgetIncome;
use BudgetBundle\Entity\BudgetSummary;
use BudgetBundle\Entity\BudgetIncomeSummary;
use BudgetBundle\Entity\PreBudgetSummary;
use BudgetBundle\Entity\PreBudgetIncomeSummary;
use Devnet\WorkflowBundle\Entity\TaskQueue;
use Devnet\WorkflowBundle\Event\FilterResponseEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BudgetWorkflowTaskLabelSubscriber implements EventSubscriberInterface
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
            'workflow.office_budget.task.label' => array(
                array('getBudgetRequestWorkflowLabel', 10)
            ),
            'workflow.fund_request.task.label' => array(
                array('getFundRequestWorkflowLabel', 1)
            ),
            'workflow.budget_compilation.task.label' => array(
                array('getBudgetCompilationWorkflowLabel', 1)
            ),
            'workflow.expense_sanction.task.label' => array(
                array('getBudgetExpenseSanctionWorkflowLabel', 1)
            ),
            'workflow.budget_income.task.label' => array(
                array('getBudgetIncomeWorkflowLabel', 1)
            ),
            'workflow.budget_expense.task.label' => array(
                array('getBudgetExpenseWorkflowLabel', 1)
            ),
            'workflow.budget_income_summary.task.label' => array(
                array('getBudgetIncomeSummaryWorkflowLabel', 1)
            ),
            'workflow.pre_budget_income_summary.task.label' => array(
                array('getPreBudgetIncomeSummaryWorkflowLabel', 1)
            ),
            'workflow.pre_budget_summary.task.label' => array(
                array('getPreBudgetSummaryWorkflowLabel', 1)
            )
        );
    }

    public function getBudgetRequestWorkflowLabel(FilterResponseEvent $event)
    {
        $year = $this->getFiscalYearFromTaskQueue($event->getTaskQueue());

        $event->setResponse(sprintf('Budget %s (%s)', $year, $event->getTaskQueue()->getOffice()->getName()));
    }

    protected function getFiscalYearFromTaskQueue(TaskQueue $taskQueue)
    {
        $entity = $this->em->getRepository($taskQueue->getEntity())->find($taskQueue->getRefId());

        return $this->getFinancialYear($entity);
    }

    protected function getFinancialYear($entity) {
        return $entity->getFinancialYear()->getLabel();
    }

    public function getFundRequestWorkflowLabel(FilterResponseEvent $event)
    {
        $event->setResponse(sprintf('Additional Budget Demand From %s', $event->getTaskQueue()->getOffice()->getName()));
    }

    public function getBudgetCompilationWorkflowLabel(FilterResponseEvent $event)
    {
        /** @var BudgetSummary $budgetSummary */
        $budgetSummary = $this->em->getRepository($event->getTaskQueue()->getEntity())->find($event->getTaskQueue()->getRefId());

        $event->setResponse($this->getBudgetCompilationLabel($budgetSummary));
    }

    protected function getBudgetCompilationLabel(BudgetSummary $budgetSummary)
    {
        $status = explode('_', $budgetSummary->getStatus())[0];

        switch ($status) {
            case 'allocation';
                $msg = 'Budget Allocation of FY'; break;
            case 'distribution';
                $msg = 'Budget Distribution of FY'; break;
            case 'amendmentrequest';
                $msg = 'Budget Amendment of FY'; break;
            case 'amendmentsanction';
                $msg = 'Budget Amendment Allocation of FY'; break;
            default:
                $msg = 'Budget Compilation of FY'; break;
        }

        return sprintf('%s %s', $msg, $budgetSummary->getFinancialYear()->getLabel());
    }

    public function getBudgetExpenseWorkflowLabel(FilterResponseEvent $event)
    {
        /** @var BudgetExpense $budgetExpense */
        $budgetExpense = $this->em->getRepository($event->getTaskQueue()->getEntity())->find($event->getTaskQueue()->getRefId());

        $msg = 'Budget Expense';
        if (!$budgetExpense) {
            $event->setResponse($msg);
        }

        return $event->setResponse(sprintf('%s - %s', 'Budget Expense', $budgetExpense->getOffice()->getName()));
    }

    public function getBudgetExpenseSanctionWorkflowLabel(FilterResponseEvent $event)
    {
        /** @var BudgetExpenseSanction $budgetExpense */
        $budgetExpense = $this->em->getRepository($event->getTaskQueue()->getEntity())->find($event->getTaskQueue()->getRefId());

        $msg = 'Budget Expense Sanction';
        if (!$budgetExpense) {
            $event->setResponse($msg);
        }

        return $event->setResponse(sprintf('%s - %s', 'Budget Expense Sanction', $budgetExpense->getOffice()->getName()));
    }

    public function getBudgetIncomeWorkflowLabel(FilterResponseEvent $event)
    {
        /** @var BudgetIncome $budgetIncome */
        $budgetIncome = $this->em->getRepository($event->getTaskQueue()->getEntity())->find($event->getTaskQueue()->getRefId());

        $msg = 'Budget Expense';
        if (!$budgetIncome) {
            $event->setResponse($msg);
        }

        return $event->setResponse(sprintf('%s - %s', 'Budget Income', $budgetIncome->getOffice()->getName()));
    }

    public function getBudgetIncomeSummaryWorkflowLabel(FilterResponseEvent $event)
    {
        /** @var BudgetIncomeSummary $budgetIncome */
        $budgetIncome = $this->em->getRepository($event->getTaskQueue()->getEntity())->find($event->getTaskQueue()->getRefId());

        $msg = 'Budget Income';
        if ($budgetIncome->isAmendmentStarted()) {
            $msg .= ' Amendment';
        }

        return $event->setResponse(sprintf('%s - %s', $msg, $budgetIncome->getFinancialYear()->getLabel()));
    }

    public function getPreBudgetIncomeSummaryWorkflowLabel(FilterResponseEvent $event)
    {
        /** @var PreBudgetIncomeSummary $budgetIncome */
        $budgetIncome = $this->em->getRepository($event->getTaskQueue()->getEntity())->find($event->getTaskQueue()->getRefId());

        return $event->setResponse(sprintf('%s - %s', 'Pre Budget Income', $budgetIncome->getFinancialYear()->getLabel()));
    }

    public function getPreBudgetSummaryWorkflowLabel(FilterResponseEvent $event)
    {
        /** @var PreBudgetSummary $budgetIncome */
        $budgetIncome = $this->em->getRepository($event->getTaskQueue()->getEntity())->find($event->getTaskQueue()->getRefId());

        return $event->setResponse(sprintf('%s - %s', 'Pre Budget', $budgetIncome->getFinancialYear()->getLabel()));
    }
}