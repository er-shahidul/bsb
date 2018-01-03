<?php

namespace BudgetBundle\Controller;

use BudgetBundle\Datatables\BudgetExpenseSanctionDatatable;
use BudgetBundle\Entity\BudgetExpenseSanction;
use BudgetBundle\Form\BudgetExpenseSanctionForm;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/expense/sanction")
 */
class BudgetExpenseSanctionController extends BudgetBaseController
{

    /**
     * @Route("/list", name="budget_expense_bill_sanction_list", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET') or is_granted('DASB_USER')")
     */
    public function listAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(BudgetExpenseSanctionDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->isGranted('DASB_USER')) {
                $qb->andWhere("budgetexpensesanction.office = :office");
                $qb->setParameter('office', $this->getOffice());
            }

            $qb->addOrderBy('budgetexpensesanction.createdAt', 'DESC');
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render(
            '@Budget/BudgetExpenseSanction/index.html.twig',
            [
                'datatable' => $datatable,
            ]
        );
    }

    /**
     * @Route("/update/{id}", name="budget_expense_bill_sanction_update", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET_CLERK') and is_granted('edit:expense_sanction', budgetExpenseSanction)")
     */
    public function updateAction(BudgetExpenseSanction $budgetExpenseSanction, Request $request)
    {
        $form = $this->createForm(BudgetExpenseSanctionForm::class, $budgetExpenseSanction);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $this->budgetExpenseSanctionRepo()->save($budgetExpenseSanction);

                $this->addFlash('success', 'Budget expense sanction has been updated successfully');

                return $this->redirect($request->request->get('_referrer'));
            }
        }

        return $this->render('@Budget/BudgetExpenseSanction/update.html.twig', [
            'form' => $form->createView(),
            'budgetExpense' => $budgetExpenseSanction->getBudgetExpense(),
        ]);
    }

    /**
     * @Route("/view/{id}", name="budget_expense_bill_sanction_view", options={"expose"=true})
     * @Security("has_role('ROLE_BUDGET') or is_granted('DASB_USER')")
     */
    public function viewAction(BudgetExpenseSanction $budgetExpenseSanction)
    {
        if ($this->isGranted('DASB_USER')) {
            $this->denyAccessUnlessGranted('SAME_OFFICE', $budgetExpenseSanction);
        }

        return $this->render('@Budget/BudgetExpenseSanction/view.html.twig', [
            'budgetExpenseSanction' => $budgetExpenseSanction,
            'budgetExpense' => $budgetExpenseSanction->getBudgetExpense(),
            'validForm' => $budgetExpenseSanction->getChequeLipiDate() && $budgetExpenseSanction->getChequeLipiNo()
        ]);
    }
}
