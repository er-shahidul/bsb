<?php

namespace AccountBundle\Controller;

use AccountBundle\Datatables\SanctionPaymentEntryDatatable;
use AccountBundle\Entity\SanctionPayment;
use AccountBundle\Form\PaymentEntryForm;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * PaymentEntry controller.
 *
 * @Route("sanction")
 */
class SanctionPaymentEntryController extends AccountBaseController
{
    /**
     *
     * @Route("/", name="account_sanction_entry_index")
     * @Method("GET")
     * @Security("has_role('ROLE_ACCOUNT') or is_granted('DASB_USER')")
     * @param Request $request
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $datatable = $this->prepareDatatable(SanctionPaymentEntryDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("sanctionpayment.office = :office");
            $qb->setParameter('office', $this->getUser()->getOffice());
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@Account/SanctionEntry/index.html.twig', array(
            'datatable' => $datatable,
            'fundTypes' => $this->getFundTypes(),
        ));
    }

    /**
     *
     * @Route("/payment/new", name="account_sanction_payment_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ACCOUNT_CLERK') or has_role('ROLE_DASB_CLERK')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $sanctionPayment = new SanctionPayment();
        $sanctionPayment->setOffice($this->getOffice());
        $sanctionPayment->setYear(date('Y'));

        $form = $this->createForm(PaymentEntryForm::class, $sanctionPayment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sanctionPayment);
            $em->flush();

            return $this->redirectToRoute('account_sanction_entry_index');
        }

        return $this->render('@Account/SanctionEntry/payment-create-update.html.twig', array(
            'sanctionPayment' => $sanctionPayment,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/payment/{id}/edit", name="account_sanction_payment_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('edit:sanction_payment_workflow', sanctionPayment) and is_granted('SAME_OFFICE', sanctionPayment)")
     * @param Request $request
     * @param SanctionPayment $sanctionPayment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, SanctionPayment $sanctionPayment)
    {
        $editForm = $this->createForm(PaymentEntryForm::class, $sanctionPayment);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $sanctionPayment->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Update Successfully');

            return $this->redirect($request->request->get('_referrer'));

        }

        return $this->render('@Account/SanctionEntry/payment-create-update.html.twig', array(
            'sanctionPayment' => $sanctionPayment,
            'form' => $editForm->createView()
        ));
    }

    /**
     *
     * @Route("/payment/{id}/view", name="account_sanction_payment_view")
     * @Security("is_granted('SAME_OFFICE', sanctionPayment)")
     * @param SanctionPayment $sanctionPayment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(SanctionPayment $sanctionPayment)
    {
        return $this->render('@Account/SanctionEntry/payment-view.html.twig', array(
            'sanctionPayment' => $sanctionPayment,
        ));
    }
}
