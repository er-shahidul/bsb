<?php

namespace AccountBundle\Controller;

use AccountBundle\Datatables\ChequeReturnDatatable;
use AccountBundle\Entity\BankAccount;
use AccountBundle\Entity\ChequeReturn;
use AccountBundle\Entity\FundType;
use AccountBundle\Entity\Reconciliation;
use AccountBundle\Form\ChequeReturnForm;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * ChequeReturnController controller.
 *
 * @Route("cheque_return")
 */
class ChequeReturnController extends AccountBaseController
{
    /**
     * @Route("/", name="account_cheque_return_index")
     * @Method("GET")
     * @Security("has_role('ROLE_ACCOUNT') or is_granted('DASB_USER')")
     * @param Request $request
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $datatable = $this->prepareDatatable(ChequeReturnDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("chequereturn.office = :office");
            $qb->setParameter('office', $this->getUser()->getOffice());
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@Account/ChequeReturn/index.html.twig', array(
            'datatable' => $datatable,
            'fundTypes' => $this->getFundTypes(),
        ));
    }


    /**
     * @Route("/new/{id}", name="account_cheque_return_new")
     * @Method({"GET", "POST"})
     * @Security("(has_role('ROLE_ACCOUNT_CLERK') or has_role('ROLE_DASB_CLERK'))")
     * @param Request $request
     * @param BankAccount $bankAccount
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, FundType $fundType)
    {
        $reconciliation = new ChequeReturn();
        $reconciliation->setFundType($fundType);
        $reconciliation->setOffice($this->getOffice());
        $reconciliation->setMonth(date('m'));
        $reconciliation->setYear(date('Y'));

        $form = $this->createForm(ChequeReturnForm::class, $reconciliation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getRepository('AccountBundle:ChequeReturn')->save($reconciliation);

            $this->addFlash('success', 'Cheque Return Created Successfully');

            return $this->redirectToRoute('account_cheque_return_index');
        }

        return $this->render('@Account/ChequeReturn/create.html.twig', array(
            'reconciliation' => $reconciliation,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit/{id}", name="account_cheque_return_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('edit:cheque_return_workflow', reconciliation) and is_granted('SAME_OFFICE', reconciliation)")
     * @param Request $request
     * @param ChequeReturn|Reconciliation $reconciliation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, ChequeReturn $reconciliation)
    {
        $form = $this->createForm(ChequeReturnForm::class, $reconciliation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getRepository('AccountBundle:ChequeReturn')->save($reconciliation);

            $this->addFlash('success', 'Cheque Return Updated Successfully');

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render('@Account/ChequeReturn/create.html.twig', array(
            'reconciliation' => $reconciliation,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/view/{id}", name="account_cheque_return_view")
     * @Security("is_granted(['ROLE_ACCOUNT', 'DASB_USER']) and is_granted('SAME_OFFICE', reconciliation)")
     * @param ChequeReturn|Reconciliation $reconciliation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(ChequeReturn $reconciliation)
    {
        return $this->render('@Account/ChequeReturn/view.html.twig', array(
            'reconciliation' => $reconciliation,
        ));
    }
}
