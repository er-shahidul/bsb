<?php

namespace BudgetBundle\Controller;

use AppBundle\Entity\FinancialYear;
use AppBundle\Utility\DateUtil;
use BudgetBundle\Datatables\BudgetSummaryDatatable;
use BudgetBundle\Datatables\PreBudgetSummaryDatatable;
use BudgetBundle\Entity\BudgetSummary;
use BudgetBundle\Entity\PreBudgetSummary;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/pre-budget")
 */
class PreBudgetController extends BudgetBaseController
{
    /**
     * @Route("/list", name="pre_budget_summary_list", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET')")
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(PreBudgetSummaryDatatable::class, $request->isXmlHttpRequest());

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render(
            '@Budget/PreBudget/index.html.twig',
            [
                'datatable'              => $datatable,
                'isBudgetSummaryExist'   => $this->getBudgetManager()->isPreBudgetSummaryExistForNextFinancialYear(),
                'nextFinancialYear'      => new FinancialYear(DateUtil::getNextFinancialYear()),
            ]
        );
    }

    /**
     * @Route("/create", name="pre_budget_summary_create")
     */
    public function createAction(Request $request)
    {
        $financialYear = $this->financialYearRepo()->find(DateUtil::getNextFinancialYear());

        $budgetSummary = $this->getDoctrine()->getRepository('BudgetBundle:PreBudgetSummary')->findOneBy(['financialYear' => $financialYear]);

        if ($budgetSummary) {
            $this->addFlash('error', 'Pre Budget already has been created for year ' . $financialYear->getId());

            return $this->redirectToRoute('pre_budget_summary_list');
        }

        $budgetSummary = $this->preBudgetSummaryRepo()->initBudgetSummary($financialYear, $this->getOffice());
        $this->addFlash('success', 'Pre Budget has been created for financial year '.$budgetSummary->getFinancialYear()->getLabel().' successfully');

        return $this->redirectToRoute('pre_budget_summary_update', ['id' => $budgetSummary->getId()]);
    }

    /**
     * @Route("/update/{id}", name="pre_budget_summary_update", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET_CLERK') and is_granted('edit:pre_budget_summary:draft', budgetSummary)")
     */
    public function updateAction(Request $request, PreBudgetSummary $budgetSummary)
    {
        if ($this->isCsrfTokenValid('budget_update', $request->request->get('_csrf_token'))) {

            $this->preBudgetSummaryDetailRepo()->updateRequestAmount($request->request->get('budget-detail'));

            $this->addFlash('success', 'Pre Budget has been updated for financial year '.$budgetSummary->getFinancialYear()->getLabel().' successfully');

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render(
            '@Budget/PreBudget/update.html.twig',
            $this->preBudgetSummaryRepo()->getPreBudgetSummaryViewUpdateData($budgetSummary)
        );
    }

    /**
     * @Route("/view/{id}", name="pre_budget_summary_view", options={"expose"=true})
     * @Security("is_granted('ROLE_BUDGET')")
     */
    public function viewAction(PreBudgetSummary $budgetSummary)
    {
        return $this->render(
            '@Budget/PreBudget/view.html.twig',
            $this->preBudgetSummaryRepo()->getPreBudgetSummaryViewUpdateData($budgetSummary)
        );
    }
}