<?php

namespace BudgetBundle\Controller;

use AppBundle\Utility\DateUtil;
use BudgetBundle\Datatables\BudgetIncomeSummaryAmendmentDatatable;
use BudgetBundle\Entity\BudgetIncomeSummary;
use BudgetBundle\Entity\BudgetSummary;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Transition;

/**
 * @Route("/budget-income-amendment")
 */
class BudgetIncomeSummaryAmendmentController extends BudgetBaseController
{
    /**
     * @Route("/list", name="budget_income_amendment_list", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET')")
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(BudgetIncomeSummaryAmendmentDatatable::class, $request->isXmlHttpRequest(), function($qb){
            /** @var QueryBuilder $qb */
            $qb->andWhere("budgetincomesummary.amendmentStarted = 1");
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        /** @var BudgetIncomeSummary $budgetSummary */
        $budgetSummary = $this->budgetIncomeSummaryRepo()->getCurrentYearBudgetSummary();

        return $this->render(
            '@Budget/BudgetIncomeSummaryAmendment/index.html.twig',
            [
                'datatable'    => $datatable,
                'budgetIncome' => $budgetSummary,
                'year'         => DateUtil::getCurrentFinancialYear(),
            ]
        );
    }

    /**
     * @Route("/create", name="budget_income_amendment_create")
     * @Security("has_role('ROLE_BUDGET_CLERK')")
     */
    public function createAction(Request $request)
    {
        /** @var BudgetSummary $budgetSummary */
        $budgetSummary = $this->budgetIncomeSummaryRepo()->getCurrentYearBudgetSummary();

        if ($budgetSummary->isAmendmentStarted()) {
            $this->addFlash('error', 'Budget amendment already created');

            return $this->redirectToRoute('budget_income_amendment_list');
        }

        $budgetSummary->setAmendmentStarted(true);
        $budgetSummary->setStatus('amendment_wait_for_clerk');
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', 'Budget amendment created successfully for ' . $budgetSummary->getFinancialYear()->getLabel());

        $workflow = $this->get('workflow.registry')->get($budgetSummary, 'budget_income_summary');
        $transition = new Transition(
            'hidden_transaction',
            'completed',
            'amendment_wait_for_clerk'
        );
        $this->dispatch('workflow.entered',
            new Event($budgetSummary, $workflow->getMarking($budgetSummary), $transition, 'budget_income_summary')
        );

        return $this->redirectToRoute('budget_income_amendment_update', ['id' => $budgetSummary->getId()]);
    }

    /**
     * @Route("/update/{id}", name="budget_income_amendment_update", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET_CLERK') and is_granted('edit:budget_income_summary:amendment_wait_for_clerk', budgetSummary)")
     */
    public function updateAction(Request $request, BudgetIncomeSummary $budgetSummary)
    {
        if ($this->isCsrfTokenValid('budget_amendment_update', $request->request->get('_csrf_token'))) {

            $this->budgetIncomeSummaryDetailRepo()->updateAmendmentRequestAmount($request->request->get('amendment-request'));

            $this->addFlash('success', 'Update Successfully');

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render(
            '@Budget/BudgetIncomeSummaryAmendment/update.html.twig',
            $this->getBudgetManager()->getBudgetIncomeSummaryData($budgetSummary)
        );
    }

    /**
     * @Route("/view/{id}", name="budget_income_amendment_view", options={"expose"=true})
     * @Security("is_granted('ROLE_BUDGET')")
     */
    public function viewAction(BudgetIncomeSummary $budgetSummary)
    {
        return $this->render(
            '@Budget/BudgetIncomeSummaryAmendment/view.html.twig',
            $this->getBudgetManager()->getBudgetIncomeSummaryData($budgetSummary)
        );
    }
}