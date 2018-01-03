<?php

namespace BudgetBundle\Controller;

use AppBundle\Entity\FinancialYear;
use AppBundle\Entity\Office;
use BudgetBundle\Datatables\BudgetSummaryDatatable;
use BudgetBundle\Entity\BudgetHead;
use BudgetBundle\Entity\BudgetSummary;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/distribution")
 */
class BudgetDistributionController extends BudgetBaseController
{
    /**
     * @Route("/update/{id}", name="budget_distribution", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET_CLERK') and is_granted('edit:budget_compilation:distribution_wait_for_clerk', budgetSummary)")
     */
    public function updateAction(Request $request, BudgetSummary $budgetSummary)
    {
        if ($this->isCsrfTokenValid('budget_sanction_to_dasb', $request->request->get('_csrf_token'))) {
            $data = $request->request->get('dasb-budget');
            if ($this->isDistributedAmountGreaterThanAllocateAmount($budgetSummary, $data)) {

                $this->addFlash('error', 'Invalid Operation');

                return $this->redirectToRoute('budget_summary_list', ['id' => $budgetSummary->getId()]);
            }

            $this->budgetDetailRepo()->updateAmount($data);
            $this->budgetSummaryDetailRepo()->updateRemainingAmount($budgetSummary);
            $this->addFlash('success', 'Budget Distribution has been updated for financial year '.$budgetSummary->getFinancialYear()->getLabel().' successfully');

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render('@Budget/BudgetDistribution/update.html.twig',
            $this->budgetSummaryRepo()->getBudgetSummaryViewUpdateData($budgetSummary)
        );
    }

    private function isDistributedAmountGreaterThanAllocateAmount(BudgetSummary $budgetSummary, $data)
    {
        $distributedAmount = 0;
        foreach ($data as $amount) {
            $distributedAmount += $amount;
        }

        $budgetTotalAmount = $this->budgetSummaryDetailRepo()->getTotalAmount($budgetSummary->getId());

        return (int)$distributedAmount > (int)$budgetTotalAmount['amount'];
    }

    /**
     * @Route("/view/{id}", name="budget_distribution_view", options={"expose"=true})
     * @Security("is_granted('ROLE_BUDGET')")
     */
    public function viewAction(BudgetSummary $budgetSummary)
    {
        if (!BudgetSummaryDatatable::canView('distribution', $budgetSummary->getStatus())) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('@Budget/BudgetDistribution/view.html.twig',
            $this->budgetSummaryRepo()->getBudgetSummaryViewUpdateData($budgetSummary)
        );
    }

    /**
     * @Route("/expense-summary/{id}/{headId}/{officeId}", name="budget_distribution_expense_summary", options={"expose"=true})
     */
    public function expenseSummaryAction(BudgetSummary $budgetSummary, $headId, $officeId)
    {
        $data['financialYear'] = $budgetSummary->getFinancialYear()->getId();
        /** @var BudgetHead $budgetHead */
        $budgetHead = $this->budgetHeadRepo()->find($headId);
        $office = $this->getDoctrine()->getRepository('AppBundle:Office')->find($officeId);
        $data['office'] = $office;
        $data['budgetHead'] = $budgetHead;
        $data['beforeTwoYear'] = $this->budgetExpenseSanctionRepo()->getTotalExpenseOfHead($budgetHead, new FinancialYear($data['financialYear'] - 3),$office);
        $data['beforePrevYear'] = $this->budgetExpenseSanctionRepo()->getTotalExpenseOfHead($budgetHead, new FinancialYear($data['financialYear'] - 2), $office);
        $data['prev'] = $this->budgetExpenseSanctionRepo()->getTotalExpenseOfHead($budgetHead, new FinancialYear($data['financialYear'] - 1), $office);

        return $this->render('@Budget/BudgetDistribution/expense-summary.html.twig', $data);
    }
}