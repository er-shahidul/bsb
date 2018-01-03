<?php

namespace BudgetBundle\Controller;

use AppBundle\Entity\FinancialYear;
use AppBundle\Utility\DateUtil;
use BudgetBundle\Datatables\BudgetDatatable;
use BudgetBundle\Entity\Budget;
use BudgetBundle\Event\BudgetEvent;
use BudgetBundle\Manager\BudgetManager;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/request")
 */
class BudgetRequestController extends BudgetBaseController
{
    /**
     * @Route("/list", name="budget_list", options={"expose"=true})
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(BudgetDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("budget.office = :office");
            $qb->setParameter('office', $this->getUser()->getOffice());
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        $budgetManager = $this->get(BudgetManager::class);
        $financialYear = new FinancialYear(DateUtil::getNextFinancialYear());
        $budget = $this->budgetRepo()->getBudgetByYear($financialYear, $this->getOffice());

        return $this->render(
            '@Budget/BudgetRequest/index.html.twig',
            [
                'datatable'       => $datatable,
                'canCreateBudget' => $budgetManager->canDASBCreateBudget(),
                'budget'          => $budget,
                'financialYear'   => $financialYear
            ]
        );
    }

    /**
     * @Route("/create", name="budget_create")
     * @Security("has_role('ROLE_DASB_CLERK') or has_role('ROLE_BUDGET_CLERK')")
     */
    public function createAction(Request $request)
    {
        $budgetRepo = $this->getDoctrine()->getRepository('BudgetBundle:Budget');

        $nextYear = DateUtil::getNextFinancialYear();
        $financialYear = $this->financialYearRepo()->find($nextYear);

        $budget = $this->budgetRepo()->findOneBy(['financialYear' => $financialYear, 'office' => $this->getOffice()]);

        if ($budget) {
            $this->addFlash('error', 'Already budget has been created of year ' . $financialYear->getLabel());

            return $this->redirectToRoute('budget_list');
        }

        $budget = $budgetRepo->initBudget($financialYear, $this->getUser()->getOffice());
        $this->dispatch('budget.created', new BudgetEvent($budget, []));

        $this->addFlash('success', 'Budget has been created for financial year '.$financialYear->getLabel().' successfully');

        return $this->redirectToRoute('budget_update', ['id' => $budget->getId()]);
    }

    /**
     * @Route("/update/{id}", name="budget_update", options={"expose"=true})
     * @Security("is_granted('SAME_OFFICE', budget) and is_granted('edit:office_budget:draft', budget)")
     */
    public function updateAction(Request $request, Budget $budget)
    {
        $budgetYear = $budget->getFinancialYear()->getId();
        $office = $this->getUser()->getOffice();

        if ($this->isCsrfTokenValid('budget_update', $request->request->get('_csrf_token'))) {

            $this->budgetDetailRepo()->updateRequestAmount($request->request->get('budgetDetail'), $request->request->get('budgetDetailRemark'));

            /** TODO: Changes data should be in BudgetEvent */
            $this->dispatch('budget.updated', new BudgetEvent($budget, []));

            $this->addFlash('success', 'Budget has been updated for financial year '.$budget->getFinancialYear()->getLabel().' successfully');

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render(
            '@Budget/BudgetRequest/update.html.twig',
            [
                'budgetHead'           => $this->getDoctrine()->getRepository('BudgetBundle:BudgetHead')->getParentBudgetHead(),
                'budget'               => $budget,
                'budgetYear'           => $budgetYear,
                'budgetAmount'         => $this->budgetDetailRepo()->getBudgetAmount($budget),
                'currentBudgetAmount'  => $this->budgetDetailRepo()->getBudgetAmountByYear($budgetYear - 1, $office),
                'previousBudgetAmount' => $this->budgetDetailRepo()->getBudgetAmountByYear($budgetYear - 2, $office),
            ]
        );
    }

    /**
     * @Route("/view/{id}", name="budget_view", options={"expose"=true})
     * @Security("is_granted('SAME_OFFICE', budget) or is_granted('ROLE_BUDGET_CLERK')")
     */
    public function viewAction(Budget $budget)
    {
        $budgetYear = $budget->getFinancialYear()->getId();
        $budgetHead = $this->getDoctrine()->getRepository('BudgetBundle:BudgetHead')->getParentBudgetHead();
        $office = $budget->getOffice();

        return $this->render(
            '@Budget/BudgetRequest/view.html.twig',
            [
                'budgetHead'           => $budgetHead,
                'budget'               => $budget,
                'budgetYear'           => $budgetYear,
                'budgetAmount'         => $this->budgetDetailRepo()->getBudgetAmountByYear($budgetYear, $office),
                'currentBudgetAmount'  => $this->budgetDetailRepo()->getBudgetAmountByYear($budgetYear - 1, $office),
                'previousBudgetAmount' => $this->budgetDetailRepo()->getBudgetAmountByYear($budgetYear - 2, $office),
            ]
        );
    }

    public function checkAccess(Budget $budget)
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN') && $budget->getOffice()->getId() != $this->getUser()->getOffice()->getId()) {
            throw $this->createAccessDeniedException();
        }

        if ($budget->getStatus() != 'draft') {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/requested_budgets", name="budget_requested_budget_list", options={"expose"=true})
     * @Security("is_granted('ROLE_BUDGET_CLERK')")
     */
    public function budgetListPageAction(Request $request)
    {
        $data['year'] = $request->query->get('year');
        if ($data['year']) {
            $data['budgets'] = $this->budgetRepo()->findBy(['financialYear' => new FinancialYear($data['year'])], ['office' => 'asc']);
            $data['amounts'] = $this->budgetDetailRepo()->getBudgetAmountByOffice($data['year']);
        }

        return $this->render('@Budget/BudgetRequest/requested-budget.html.twig', $data);
    }
}
