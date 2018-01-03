<?php

namespace BudgetBundle\Controller;

use BudgetBundle\Datatables\BudgetSummaryDatatable;
use BudgetBundle\Entity\BudgetSummary;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/allocation")
 */
class BudgetAllocationController extends BudgetBaseController
{
    /**
     * @Route("/update/{id}", name="budget_allocation", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET_CLERK') and is_granted('edit:budget_compilation:allocation_wait_for_clerk', budgetSummary)")
     */
    public function updateAction(Request $request, BudgetSummary $budgetSummary)
    {
        if ($this->isCsrfTokenValid('budget_sanction_to_basb', $request->request->get('_csrf_token'))) {

            $this->budgetSummaryDetailRepo()->updateAmount($request->request->get('budget-amount'));


            $this->addFlash('success', 'Budget allocation has been updated for financial year '.$budgetSummary->getFinancialYear()->getLabel().' successfully');

            return $this->redirect($request->request->get('_referrer'));
        }

        $year = $budgetSummary->getFinancialYear()->getId();

        return $this->render(
            '@Budget/BudgetAllocation/update.html.twig',
            [
                'budgetAmount'  => $this->budgetSummaryDetailRepo()->getBudgetSummaryAmountByYear($year),
                'budgetSummary' => $budgetSummary,
                'budgetYear'    => $year,
                'budgetHead'    => $this->getDoctrine()->getRepository('BudgetBundle:BudgetHead')->getParentBudgetHead(),
            ]
        );
    }

    /**
     * @Route("/view/{id}", name="budget_allocation_view", options={"expose"=true})
     * @Security("is_granted('ROLE_BUDGET')")
     */
    public function viewAction(BudgetSummary $budgetSummary)
    {
        if (!BudgetSummaryDatatable::canView('allocation', $budgetSummary->getStatus())) {
            throw $this->createAccessDeniedException();
        }

        $year = $budgetSummary->getFinancialYear()->getId();

        return $this->render(
            '@Budget/BudgetAllocation/view.html.twig',
            [
                'budgetAmount'  => $this->budgetSummaryDetailRepo()->getBudgetSummaryAmountByYear($year),
                'budgetSummary' => $budgetSummary,
                'budgetYear'    => $year,
                'budgetHead'    => $this->getDoctrine()->getRepository('BudgetBundle:BudgetHead')->getParentBudgetHead(),
            ]
        );
    }
}