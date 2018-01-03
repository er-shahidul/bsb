<?php

namespace AccountBundle\Controller;

use AccountBundle\Datatables\BankAccountDatatable;
use AccountBundle\Entity\BankAccount;
use AccountBundle\Form\BankAccountForm;
use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * BankAccount controller.
 *
 * @Route("bank-account")
 */
class BankAccountController extends BaseController
{
    /**
     *
     * @Route("/", name="account_bank_account_index")
     * @Method("GET")
     * @Security("has_role('ROLE_ACCOUNT') or is_granted('DASB_USER')")
     */
    public function indexAction(Request $request)
    {
        $datatable = $this->prepareDatatable(BankAccountDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("bankaccount.office = :office");
            $qb->setParameter('office', $this->getUser()->getOffice());
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@Account/BankAccount/index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     *
     * @Route("/new", name="account_bank_account_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ACCOUNT_CLERK') or has_role('ROLE_DASB_CLERK')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $bankAccount = new BankAccount();
        $bankAccount->setOffice($this->getOffice());
        $form = $this->createForm(BankAccountForm::class, $bankAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bankAccount);
            $em->flush();

            return $this->redirectToRoute('account_bank_account_index');
        }

        return $this->render('@Account/BankAccount/new.html.twig', array(
            'bankAccount' => $bankAccount,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", name="account_bank_account_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('edit:accounts_bank_account', bankAccount) and is_granted('SAME_OFFICE', bankAccount)")
     * @param Request $request
     * @param BankAccount $bankAccount
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, BankAccount $bankAccount)
    {
        $editForm = $this->createForm(BankAccountForm::class, $bankAccount);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render('@Account/BankAccount/edit.html.twig', array(
            'fundHead' => $bankAccount,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * @Route("/{id}/view", name="account_bank_account_view")
     * @Security("(has_role('ROLE_ACCOUNT_CLERK') or has_role('ROLE_DASB_CLERK')) and is_granted('SAME_OFFICE', bankAccount)")
     */
    public function viewAction(Request $request, BankAccount $bankAccount)
    {
        return $this->render('@Account/BankAccount/view.html.twig', array(
            'bankAccount' => $bankAccount
        ));
    }
}
