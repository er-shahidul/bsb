<?php

namespace BudgetBundle\Controller;

use AppBundle\Entity\FinancialYear;
use AppBundle\Utility\DateUtil;
use BudgetBundle\Datatables\BudgetSummaryDatatable;
use BudgetBundle\Datatables\PreBudgetIncomeSummaryDatatable;
use BudgetBundle\Datatables\PreBudgetSummaryDatatable;
use BudgetBundle\Entity\BudgetSummary;
use BudgetBundle\Entity\PreBudgetIncomeSummary;
use BudgetBundle\Entity\PreBudgetSummary;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/pre-budget-income")
 */
class PreBudgetIncomeController extends BudgetBaseController
{
    /**
     * @Route("/list", name="pre_budget_income_summary_list", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET')")
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(PreBudgetIncomeSummaryDatatable::class, $request->isXmlHttpRequest());

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render(
            '@Budget/PreBudgetIncome/index.html.twig',
            [
                'datatable'              => $datatable,
                'isBudgetSummaryExist'   => $this->getBudgetManager()->isPreBudgetIncomeSummaryExistForNextFinancialYear(),
                'nextFinancialYear'      => new FinancialYear(DateUtil::getNextFinancialYear()),
            ]
        );
    }

    /**
     * @Route("/create", name="pre_budget_income_summary_create")
     */
    public function createAction(Request $request)
    {
        $financialYear = $this->financialYearRepo()->find(DateUtil::getNextFinancialYear());

        $budgetSummary = $this->getDoctrine()->getRepository('BudgetBundle:PreBudgetIncomeSummary')->findOneBy(['financialYear' => $financialYear]);

        if ($budgetSummary) {
            $this->addFlash('error', 'Pre Budget Income already has been created for year ' . $financialYear->getId());

            return $this->redirectToRoute('pre_budget_income_summary_list');
        }

        $budgetSummary = $this->preBudgetIncomeSummaryRepo()->initBudgetSummary($financialYear, $this->getOffice());
        $this->addFlash('success', 'Pre Budget Income has been created for financial year '.$budgetSummary->getFinancialYear()->getLabel().' successfully');

        return $this->redirectToRoute('pre_budget_income_summary_update', ['id' => $budgetSummary->getId()]);
    }

    /**
     * @Route("/update/{id}", name="pre_budget_income_summary_update", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET_CLERK') and is_granted('edit:pre_budget_income_summary:draft', budgetSummary)")
     */
    public function updateAction(Request $request, PreBudgetIncomeSummary $budgetSummary)
    {
        if ($this->isCsrfTokenValid('budget_update', $request->request->get('_csrf_token'))) {

            $this->preBudgetIncomeSummaryDetailRepo()->updateRequestAmount($request->request->get('budget-detail'));

            $this->addFlash('success', 'Pre Budget Income has been updated for financial year '.$budgetSummary->getFinancialYear()->getLabel().' successfully');

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render(
            '@Budget/PreBudgetIncome/update.html.twig',
            $this->preBudgetIncomeSummaryRepo()->getPreBudgetSummaryViewUpdateData($budgetSummary)
        );
    }

    /**
     * @Route("/view/{id}", name="pre_budget_income_summary_view", options={"expose"=true})
     * @Security("is_granted('ROLE_BUDGET')")
     */
    public function viewAction(PreBudgetIncomeSummary $budgetSummary)
    {
        return $this->render(
            '@Budget/PreBudgetIncome/view.html.twig',
            $this->preBudgetIncomeSummaryRepo()->getPreBudgetSummaryViewUpdateData($budgetSummary)
        );
    }
}