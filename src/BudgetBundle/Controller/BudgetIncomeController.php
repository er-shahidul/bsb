<?php

namespace BudgetBundle\Controller;

use BudgetBundle\Datatables\BudgetIncomeDatatable;
use BudgetBundle\Entity\BudgetExpense;
use BudgetBundle\Entity\BudgetHead;
use BudgetBundle\Entity\BudgetIncome;
use BudgetBundle\Form\BudgetExpenseForm;
use BudgetBundle\Form\BudgetIncomeForm;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/income")
 */
class BudgetIncomeController extends BudgetBaseController
{
    /**
     * @Route("/list", name="budget_income_list", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET') or is_granted('DASB_USER')")
     */
    public function indexAction(Request $request)
    {
        /** @var AbstractDatatableView|Response $datatable */
        $datatable = $this->prepareDatatable(BudgetIncomeDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("budgetincome.office = :office");
            $qb->setParameter('office', $this->getOffice());

            $qb->addOrderBy('budgetincome.createdAt', 'DESC');
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render(
            '@Budget/BudgetIncome/index.html.twig',
            [
                'datatable' => $datatable,
            ]
        );
    }

    /**
     * @Route("/create", name="budget_income_create")
     * @Security("has_role('ROLE_BUDGET_CLERK') or has_role('ROLE_DASB_CLERK')")
     */
    public function createAction(Request $request)
    {
        $currentFinancialYear = $this->financialYearRepo()->getActiveFinancialYear();
        $budgetIncome = new BudgetIncome();
        $budgetIncome->setOffice($this->getOffice());
        $budgetIncome->setFinancialYear($currentFinancialYear);
        $form = $this->createForm(BudgetIncomeForm::class, $budgetIncome);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $this->budgetExpenseRepo()->save($budgetIncome);

                $this->addFlash('success', 'Non tax income has been created successfully');

                return $this->redirectToRoute('budget_income_list');
            }
        }

        return $this->render('@Budget/BudgetIncome/create.html.twig', [
            'form' => $form->createView(),
            'financialYear' => $currentFinancialYear
        ]);
    }

    /**
     * @Route("/update/{id}", name="budget_income_update", options={"expose"=true})
     * @Security("(has_role('ROLE_BUDGET_CLERK') or has_role('ROLE_DASB_CLERK')) and is_granted('edit:budget_income:draft', budgetIncome)")
     */
    public function updateAction(BudgetIncome $budgetIncome, Request $request)
    {
        $currentFinancialYear = $budgetIncome->getFinancialYear();
        $form = $this->createForm(BudgetIncomeForm::class, $budgetIncome);
        $form->remove('saveAndAdd');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $this->budgetExpenseRepo()->save($budgetIncome);

                $this->addFlash('success', 'Non tax income has been updated successfully');

                return $this->redirectToRoute('budget_income_list');
            }
        }

        return $this->render('@Budget/BudgetIncome/create.html.twig', [
            'form' => $form->createView(),
            'financialYear' => $currentFinancialYear,
            'budgetExpense' => $budgetIncome
        ]);
    }

    /**
     * @Route("/view/{id}", name="budget_income_view", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET') or is_granted('DASB_USER')")
     */
    public function viewAction(BudgetIncome $budgetIncome)
    {
        $this->denyAccessUnlessGranted('SAME_OFFICE', $budgetIncome);

        return $this->render('@Budget/BudgetIncome/view.html.twig', [
            'budgetIncome' => $budgetIncome
        ]);
    }
}
