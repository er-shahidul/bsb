<?php

namespace BudgetBundle\Controller;

use AppBundle\Controller\BaseController;
use BudgetBundle\Manager\BudgetManager;

class BudgetBaseController extends BaseController
{
    protected function getBudgetManager()
    {
        return $this->get(BudgetManager::class);
    }

    /**
     * @return \BudgetBundle\Repository\BudgetDetailRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function budgetDetailRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:BudgetDetail');
    }

    /**
     * @return \BudgetBundle\Repository\BudgetRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function budgetRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:Budget');
    }

    /**
     * @return \BudgetBundle\Repository\FundRequestRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function fundRequestRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:FundRequest');
    }

    /**
     * @return \BudgetBundle\Repository\BaseBudgetRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function baseBudgetRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:BaseBudget');
    }

    /**
     * @return \AppBundle\Repository\FinancialYearRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function financialYearRepo()
    {
        return $this->getDoctrine()->getRepository('AppBundle:FinancialYear');
    }

    /**
     * @return \BudgetBundle\Repository\BudgetSummaryDetailRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function budgetSummaryDetailRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:BudgetSummaryDetail');
    }

    /**
     * @return \BudgetBundle\Repository\BudgetSummaryRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function budgetSummaryRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:BudgetSummary');
    }

    /**
     * @return \BudgetBundle\Repository\BudgetExpenseRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function budgetExpenseRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:BudgetExpense');
    }

    /**
     * @return \BudgetBundle\Repository\BudgetIncomeRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function budgetIncomeRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:BudgetIncome');
    }

    /**
     * @return \BudgetBundle\Repository\BudgetIncomeHeadRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function budgetIncomeHeadRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:BudgetIncomeHead');
    }

    /**
     * @return \BudgetBundle\Repository\BudgetExpenseSanctionRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function budgetExpenseSanctionRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:BudgetExpenseSanction');
    }

    /**
     * @return \BudgetBundle\Repository\BudgetHeadRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function budgetHeadRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:BudgetHead');
    }

    /**
     * @return \BudgetBundle\Repository\PreBudgetSummaryDetailRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function preBudgetSummaryDetailRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:PreBudgetSummaryDetail');
    }

    /**
     * @return \BudgetBundle\Repository\PreBudgetSummaryRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function preBudgetSummaryRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:PreBudgetSummary');
    }

    /**
     * @return \BudgetBundle\Repository\PreBudgetIncomeSummaryDetailRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function preBudgetIncomeSummaryDetailRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:PreBudgetIncomeSummaryDetail');
    }

    /**
     * @return \BudgetBundle\Repository\PreBudgetIncomeSummaryRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function preBudgetIncomeSummaryRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:PreBudgetIncomeSummary');
    }

    /**
     * @return \BudgetBundle\Repository\BudgetIncomeSummaryDetailRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function budgetIncomeSummaryDetailRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:BudgetIncomeSummaryDetail');
    }

    /**
     * @return \BudgetBundle\Repository\BudgetIncomeSummaryRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function budgetIncomeSummaryRepo()
    {
        return $this->getDoctrine()->getRepository('BudgetBundle:BudgetIncomeSummary');
    }

}