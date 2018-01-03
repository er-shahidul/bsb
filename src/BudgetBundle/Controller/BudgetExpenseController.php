<?php

namespace BudgetBundle\Controller;

use BudgetBundle\Datatables\BudgetExpenseDatatable;
use BudgetBundle\Entity\Budget;
use BudgetBundle\Entity\BudgetExpense;
use BudgetBundle\Entity\BudgetHead;
use BudgetBundle\Form\BudgetExpenseForm;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/expense")
 */
class BudgetExpenseController extends BudgetBaseController
{
    /**
     * @Route("/list", name="budget_expense_list", options={"expose"=true})
     * @Security("is_granted('ROLE_BUDGET') or is_granted('DASB_USER')")
     */
    public function indexAction(Request $request)
    {
        /** @var AbstractDatatableView|Response $datatable */
        $datatable = $this->prepareDatatable(BudgetExpenseDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("budgetexpense.office = :office");
            $qb->setParameter('office', $this->getOffice());

            $qb->addOrderBy('budgetexpense.createdAt', 'DESC');
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render(
            '@Budget/BudgetExpense/index.html.twig',
            [
                'datatable' => $datatable,
            ]
        );
    }

    /**
     * @Route("/create", name="budget_expense_create")
     * @Security("has_role('ROLE_BUDGET_CLERK') or has_role('ROLE_DASB_CLERK')")
     */
    public function createAction(Request $request)
    {
        $currentFinancialYear = $this->financialYearRepo()->getActiveFinancialYear();
        $budgetExpense = new BudgetExpense();
        $budgetExpense->setOffice($this->getOffice());
        $budgetExpense->setFinancialYear($currentFinancialYear);
        $form = $this->createForm(BudgetExpenseForm::class, $budgetExpense);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $this->budgetExpenseRepo()->save($budgetExpense);

                $this->addFlash('success', 'Budget expense has been created successfully');

                return $this->redirectToRoute('budget_expense_list');
            }
        }

        return $this->render('@Budget/BudgetExpense/create.html.twig', [
            'form' => $form->createView(),
            'financialYear' => $currentFinancialYear
        ]);
    }

    /**
     * @Route("/update/{id}", name="budget_expense_update", options={"expose"=true})
     * @Security("(has_role('ROLE_BUDGET_CLERK') or has_role('ROLE_DASB_CLERK')) and is_granted('edit:budget_expense:draft', budgetExpense)")
     */
    public function updateAction(BudgetExpense $budgetExpense, Request $request)
    {
        $currentFinancialYear = $budgetExpense->getFinancialYear();
        $form = $this->createForm(BudgetExpenseForm::class, $budgetExpense);
        $form->remove('saveAndAdd');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $this->budgetExpenseRepo()->save($budgetExpense);

                $this->addFlash('success', 'Budget expense has been updated successfully');

                return $this->redirect($request->request->get('_referrer'));
            }
        }

        return $this->render('@Budget/BudgetExpense/create.html.twig', [
            'form' => $form->createView(),
            'financialYear' => $currentFinancialYear,
            'budgetExpense' => $budgetExpense
        ]);
    }

    /**
     * @Route("/view/{id}", name="budget_expense_view", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET') or is_granted('DASB_USER')")
     */
    public function viewAction(BudgetExpense $budgetExpense)
    {
        $this->denyAccessUnlessGranted('SAME_OFFICE', $budgetExpense);

        return $this->render('@Budget/BudgetExpense/view.html.twig', [
            'budgetExpense' => $budgetExpense
        ]);
    }

    /**
     * @Route("/expense-summary/{id}", name="budget_expense_summary_of_budget_head", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET') or has_role('ROLE_DASB_CLERK')")
     */
    public function getBudgetHeadExpenseAction(BudgetHead $budgetHead)
    {
        /** @var Budget $budget */
        $budget = $this->budgetRepo()->getCurrentYearBudget($this->getOffice());
        if ($budget) {
            $this->denyAccessUnlessGranted('SAME_OFFICE', $budget);

            return new JsonResponse([
                'expense' => (float)$this->budgetExpenseSanctionRepo()->getTotalExpenseOfHead($budgetHead, $budget->getFinancialYear(), $this->getOffice()),
                'budget' => (float)$this->budgetDetailRepo()->getBudgetAmountOfHead($budget, $budgetHead)
            ]);
        }

        return new JsonResponse([
            'expense' => 0,
            'budget' =>  0
        ]);
    }
}
