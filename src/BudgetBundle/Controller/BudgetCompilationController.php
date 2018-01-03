<?php

namespace BudgetBundle\Controller;

use AppBundle\Entity\FinancialYear;
use AppBundle\Utility\DateUtil;
use BudgetBundle\Datatables\BudgetSummaryDatatable;
use BudgetBundle\Entity\BudgetSummary;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/compilation")
 */
class BudgetCompilationController extends BudgetBaseController
{
    /**
     * @Route("/list", name="budget_summary_list", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET')")
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(BudgetSummaryDatatable::class, $request->isXmlHttpRequest());

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render(
            '@Budget/BudgetCompilation/index.html.twig',
            [
                'datatable'              => $datatable,
                'isBudgetSummaryExist'   => $this->getBudgetManager()->isBudgetSummaryExistForNextFinancialYear(),
                'preBudget'              => $this->preBudgetSummaryRepo()->findOneBy(['financialYear' => new FinancialYear(DateUtil::getNextFinancialYear())]),
                'budgetRequestedOffices' => $this->getBudgetManager()->getOfficesWhoRequestedBudgetForNextFinancialYear(),
                'nextFinancialYear'      => new FinancialYear(DateUtil::getNextFinancialYear()),
            ]
        );
    }

    /**
     * @Route("/create", name="budget_summary_create")
     */
    public function createAction(Request $request)
    {
        $financialYear = $this->financialYearRepo()->find(DateUtil::getNextFinancialYear());

        $budgetSummary = $this->getDoctrine()->getRepository('BudgetBundle:BudgetSummary')->findOneBy(['financialYear' => $financialYear]);

        if ($budgetSummary) {
            $this->addFlash('error', 'Already budget created of year ' . $financialYear->getId());

            return $this->redirectToRoute('budget_summary_list');
        }

        $preBudget = $this->preBudgetSummaryRepo()->findOneBy(['financialYear' => new FinancialYear(DateUtil::getNextFinancialYear())]);
        $budgetRequestedOffices = $this->getBudgetManager()->getOfficesWhoRequestedBudgetForNextFinancialYear();

        if ($preBudget && $preBudget->getStatus() != 'approved') {
            $this->addFlash('error', 'Pre Budget not approved yet');

            return $this->redirectToRoute('budget_summary_list');
        }

        $budgetSummary = $this->budgetSummaryRepo()->initBudgetSummary($financialYear, $preBudget, $this->getOffice());
        $this->addFlash('success', 'Budget has been compiled for financial year '.$budgetSummary->getFinancialYear()->getLabel().' successfully');

        return $this->redirectToRoute('budget_summary_update', ['id' => $budgetSummary->getId()]);
    }

    /**
     * @Route("/update/{id}", name="budget_summary_update", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET_CLERK') and is_granted('edit:budget_compilation:draft', budgetSummary)")
     */
    public function updateAction(Request $request, BudgetSummary $budgetSummary)
    {
        if ($this->isCsrfTokenValid('budget_update', $request->request->get('_csrf_token'))) {

            $this->budgetSummaryDetailRepo()->updateRequestAmount($request->request->get('budget-detail'));

            $this->addFlash('success', 'Budget compilation has been updated for financial year '.$budgetSummary->getFinancialYear()->getLabel().' successfully');

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render(
            '@Budget/BudgetCompilation/update.html.twig',
            $this->budgetSummaryRepo()->getBudgetSummaryViewUpdateData($budgetSummary)
        );
    }

    /**
     * @Route("/view/{id}", name="budget_summary_view", options={"expose"=true})
     * @Security("is_granted('ROLE_BUDGET')")
     */
    public function viewAction(BudgetSummary $budgetSummary)
    {
        return $this->render(
            '@Budget/BudgetCompilation/view.html.twig',
            $this->budgetSummaryRepo()->getBudgetSummaryViewUpdateData($budgetSummary)
        );
    }
}