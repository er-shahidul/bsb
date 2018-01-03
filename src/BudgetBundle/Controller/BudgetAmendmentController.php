<?php

namespace BudgetBundle\Controller;

use AppBundle\Entity\FinancialYear;
use AppBundle\Utility\DateUtil;
use BudgetBundle\Datatables\BudgetAmendmentDatatable;
use BudgetBundle\Datatables\BudgetSummaryDatatable;
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
 * @Route("/amendment")
 */
class BudgetAmendmentController extends BudgetBaseController
{
    /**
     * @Route("/list", name="budget_amendment_list", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET')")
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(BudgetAmendmentDatatable::class, $request->isXmlHttpRequest(), function($qb){
            /** @var QueryBuilder $qb */
            $qb->andWhere("budgetsummary.amendmentStarted = :amendmentStarted");
            $qb->setParameter('amendmentStarted', true);
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        /** @var BudgetSummary $budgetSummary */
        $budgetSummary = $this->budgetSummaryRepo()->getCurrentYearBudgetSummary();

        return $this->render(
            '@Budget/BudgetAmendment/index.html.twig',
            [
                'datatable'          => $datatable,
                'canCreateAmendment' => $this->getBudgetManager()->canCreateAmendment($budgetSummary),
                'budgetSummary'     => $budgetSummary
            ]
        );
    }

    /**
     * @Route("/create", name="budget_amendment_create")
     * @Security("has_role('ROLE_BUDGET_CLERK')")
     */
    public function createAction(Request $request)
    {
        /** @var BudgetSummary $budgetSummary */
        $budgetSummary = $this->budgetSummaryRepo()->getCurrentYearBudgetSummary();

        if ($budgetSummary->isAmendmentStarted() || !$this->getBudgetManager()->canCreateAmendment($budgetSummary)) {
            $this->addFlash('error', 'Budget amendment already started or date has been expired');

            return $this->redirectToRoute('budget_amendment_list');
        }

        $budgetSummary->setAmendmentStarted(true);
        $budgetSummary->setStatus('amendmentrequest_wait_for_clerk');
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', 'Budget amendment created successfully for ' . $budgetSummary->getFinancialYear()->getLabel());

        $workflow = $this->get('workflow.registry')->get($budgetSummary, 'budget_compilation');
        $transition = new Transition(
            'hidden_transaction',
            'completed',
            'amendmentrequest_wait_for_clerk'
        );
        $this->dispatch('workflow.entered',
            new Event($budgetSummary, $workflow->getMarking($budgetSummary), $transition, 'budget_compilation')
        );

        return $this->redirectToRoute('budget_amendment_update', ['id' => $budgetSummary->getId()]);
    }

    /**
     * @Route("/update/{id}", name="budget_amendment_update", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET_CLERK') and is_granted('edit:budget_compilation:amendmentrequest_wait_for_clerk', budgetSummary)")
     */
    public function updateAction(Request $request, BudgetSummary $budgetSummary)
    {
        if ($this->isCsrfTokenValid('budget_amendment_update', $request->request->get('_csrf_token'))) {

            $this->budgetSummaryDetailRepo()->updateAmendmentRequestAmount($request->request->get('amendment-request'));

            $this->addFlash('success', 'Update Successfully');

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render(
            '@Budget/BudgetAmendment/update.html.twig',
            $this->getBudgetManager()->getBudgetSummaryData($budgetSummary)
        );
    }

    /**
     * @Route("/view/{id}", name="budget_amendment_view", options={"expose"=true})
     * @Security("is_granted('ROLE_BUDGET')")
     */
    public function viewAction(BudgetSummary $budgetSummary)
    {
        return $this->render(
            '@Budget/BudgetAmendment/view.html.twig',
            $this->getBudgetManager()->getBudgetSummaryData($budgetSummary)
        );
    }

    /**
     * @Route("/update/sanction/{id}", name="budget_amendment_sanction_update", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET_CLERK') and is_granted('edit:budget_compilation:amendmentsanction_wait_for_clerk', budgetSummary)")
     */
    public function updateSanctionAction(Request $request, BudgetSummary $budgetSummary)
    {
        if ($this->isCsrfTokenValid('budget_amendment_sanction_update', $request->request->get('_csrf_token'))) {

            $this->budgetSummaryDetailRepo()->updateAmendmentSanctionAmount($request->request->get('amendment-request'));

            $this->addFlash('success', 'Update Successfully');

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render(
            '@Budget/BudgetAmendment/update-sanction.html.twig',
            $this->getBudgetManager()->getBudgetSummaryData($budgetSummary)
        );
    }

    /**
     * @Route("/view/sanction/{id}", name="budget_amendment_sanction_view", options={"expose"=true})
     * @Security("is_granted('ROLE_BUDGET')")
     */
    public function viewSanctionAction(BudgetSummary $budgetSummary)
    {
        return $this->render(
            '@Budget/BudgetAmendment/view-sanction.html.twig',
            $this->getBudgetManager()->getBudgetSummaryData($budgetSummary)
        );
    }
}