<?php

namespace BudgetBundle\Controller;

use AppBundle\Controller\BaseController;
use BudgetBundle\Entity\BudgetHead;
use BudgetBundle\Entity\BudgetIncome;
use BudgetBundle\Entity\BudgetIncomeHead;
use BudgetBundle\Form\BudgetHeadForm;
use BudgetBundle\Form\BudgetIncomeForm;
use BudgetBundle\Form\BudgetIncomeHeadForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Budgethead controller.
 *
 * @Route("budgethead")
 */
class BudgetHeadController extends BaseController
{
    /**
     * Lists all budgetHead entities.
     *
     * @Route("/expense", name="budgethead_expense_index")
     * @Method("GET")
     */
    public function expenseIndexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $budgetHeads = $em->getRepository('BudgetBundle:BudgetHead')->getParentBudgetHead();

        return $this->render('@Budget/BudgetHead/expense-index.html.twig', array(
            'budgetHeads' => $budgetHeads,
        ));
    }

    /**
     * Creates a new budgetHead entity.
     *
     * @Route("/expense/new", name="budgethead_expense_new")
     * @Method({"GET", "POST"})
     */
    public function expenseNewAction(Request $request)
    {
        $budgetHead = new Budgethead();
        $form = $this->createForm(BudgetHeadForm::class, $budgetHead);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($budgetHead);
            $em->flush();

            return $this->redirectToRoute('budgethead_expense_index');
        }

        return $this->render('@Budget/BudgetHead/expense-new.html.twig', array(
            'budgetHead' => $budgetHead,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing budgetHead entity.
     *
     * @Route("/expense/{id}/edit", name="budgethead_expense_edit")
     * @Method({"GET", "POST"})
     */
    public function expenseEditAction(Request $request, BudgetHead $budgetHead)
    {
        $editForm = $this->createForm(BudgetHeadForm::class, $budgetHead);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('budgethead_expense_index');
        }

        return $this->render('@Budget/BudgetHead/expense-edit.html.twig', array(
            'budgetHead' => $budgetHead,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Lists all budgetHead entities.
     *
     * @Route("/income", name="budgethead_income_index")
     * @Method("GET")
     */
    public function incomeIndexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $budgetHeads = $em->getRepository('BudgetBundle:BudgetIncomeHead')->getParentBudgetHead();

        return $this->render('@Budget/BudgetHead/income-index.html.twig', array(
            'budgetHeads' => $budgetHeads,
        ));
    }

    /**
     * Creates a new budgetHead entity.
     *
     * @Route("/income/new", name="budgethead_income_new")
     * @Method({"GET", "POST"})
     */
    public function incomeNewAction(Request $request)
    {
        $budgetHead = new BudgetIncomeHead();
        $form = $this->createForm(BudgetIncomeHeadForm::class, $budgetHead);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($budgetHead);
            $em->flush();

            return $this->redirectToRoute('budgethead_income_index');
        }

        return $this->render('@Budget/BudgetHead/income-new.html.twig', array(
            'budgetHead' => $budgetHead,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing budgetHead entity.
     *
     * @Route("/income/{id}/edit", name="budgethead_income_edit")
     * @Method({"GET", "POST"})
     */
    public function incomeEditAction(Request $request, BudgetIncomeHead $budgetHead)
    {
        $editForm = $this->createForm(BudgetIncomeHeadForm::class, $budgetHead);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('budgethead_income_index');
        }

        return $this->render('@Budget/BudgetHead/income-edit.html.twig', array(
            'budgetHead' => $budgetHead,
            'edit_form' => $editForm->createView(),
        ));
    }
}
