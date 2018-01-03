<?php

namespace BudgetBundle\Controller;

use AppBundle\Entity\FinancialYear;
use AppBundle\Utility\DateUtil;
use BudgetBundle\Datatables\BudgetIncomeSummaryDatatable;
use BudgetBundle\Entity\BudgetIncomeSummary;
use BudgetBundle\Entity\BudgetSummary;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/budget-income-preparation")
 */
class BudgetIncomeSummaryController extends BudgetBaseController
{
    /**
     * @Route("/list", name="budget_income_summary_list", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET')")
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(BudgetIncomeSummaryDatatable::class, $request->isXmlHttpRequest());

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render(
            '@Budget/BudgetIncomeSummary/index.html.twig',
            [
                'datatable'              => $datatable,
                'isBudgetSummaryExist'   => $this->getBudgetManager()->isBudgetIncomeSummaryExistForNextFinancialYear(),
                'preBudget'              => $this->preBudgetIncomeSummaryRepo()->findOneBy(['financialYear' => new FinancialYear(DateUtil::getNextFinancialYear())]),
                'nextFinancialYear'      => new FinancialYear(DateUtil::getNextFinancialYear()),
            ]
        );
    }

    /**
     * @Route("/create", name="budget_income_summary_create")
     */
    public function createAction(Request $request)
    {
        $financialYear = $this->financialYearRepo()->find(DateUtil::getNextFinancialYear());

        $budgetSummary = $this->getDoctrine()->getRepository('BudgetBundle:BudgetIncomeSummary')->findOneBy(['financialYear' => $financialYear]);

        if ($budgetSummary) {
            $this->addFlash('error', 'Budget Income created of year ' . $financialYear->getId());

            return $this->redirectToRoute('budget_income_summary_list');
        }

        $preBudget = $this->preBudgetIncomeSummaryRepo()->findOneBy(['financialYear' => new FinancialYear(DateUtil::getNextFinancialYear())]);

        if ($preBudget && $preBudget->getStatus() != 'approved') {
            $this->addFlash('error', 'Pre Budget Income not approved yet');

            return $this->redirectToRoute('budget_income_summary_list');
        }

        $budgetSummary = $this->budgetIncomeSummaryRepo()->initBudgetSummary($financialYear, $preBudget, $this->getOffice());
        $this->addFlash('success', 'Budget Income has been created for financial year '.$budgetSummary->getFinancialYear()->getLabel().' successfully');

        return $this->redirectToRoute('budget_income_summary_update', ['id' => $budgetSummary->getId()]);
    }

    /**
     * @Route("/update/{id}", name="budget_income_summary_update", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET_CLERK') and is_granted('edit:budget_income_summary:draft', budgetSummary)")
     */
    public function updateAction(Request $request, BudgetIncomeSummary $budgetSummary)
    {
        if ($this->isCsrfTokenValid('budget_update', $request->request->get('_csrf_token'))) {

            $this->budgetIncomeSummaryDetailRepo()->updateRequestAmount($request->request->get('budget-detail'));

            $this->addFlash('success', 'Budget Income has been updated for financial year '.$budgetSummary->getFinancialYear()->getLabel().' successfully');

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render(
            '@Budget/BudgetIncomeSummary/update.html.twig',
            $this->budgetIncomeSummaryRepo()->getBudgetSummaryViewUpdateData($budgetSummary)
        );
    }

    /**
     * @Route("/view/{id}", name="budget_income_summary_view", options={"expose"=true})
     * @Security("is_granted('ROLE_BUDGET')")
     */
    public function viewAction(BudgetIncomeSummary $budgetSummary)
    {
        return $this->render(
            '@Budget/BudgetIncomeSummary/view.html.twig',
            $this->budgetIncomeSummaryRepo()->getBudgetSummaryViewUpdateData($budgetSummary)
        );
    }
}