<?php

namespace AccountBundle\Controller;

use AccountBundle\Datatables\ReceiveEntryDatatable;
use AccountBundle\Entity\FundType;
use AccountBundle\Entity\ReceiveVoucher;
use AccountBundle\Entity\VoucherDetail;
use AccountBundle\Form\ReceiveEntryForm;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * ReceiveEntryController controller.
 *
 * @Route("sanction/receive")
 */
class ReceiveEntryController extends AccountBaseController
{
    /**
     * @Route("/", name="account_sanction_receive_index")
     * @Method("GET")
     * @Security("has_role('ROLE_ACCOUNT') or is_granted('DASB_USER')")
     * @param Request $request
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $datatable = $this->prepareDatatable(ReceiveEntryDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("receivevoucher.office = :office");
            $qb->setParameter('office', $this->getUser()->getOffice());
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@Account/ReceivePayment/index.html.twig', array(
            'datatable' => $datatable,
            'fundTypes' => $this->getFundTypes(),
        ));
    }

    /**
     * @Route("/new/{fundType}", name="account_sanction_receive_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ACCOUNT_CLERK') or has_role('ROLE_DASB_CLERK')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, FundType $fundType)
    {
        $receiveVoucher = new ReceiveVoucher();
        $receiveVoucher->setFundType($fundType);
        $receiveVoucher->setOffice($this->getOffice());

        foreach ($this->fundHead()->fundHeadByFundType($fundType, $this->getOffice()->getOfficeType()) as $fundHead) {
            $vd = new VoucherDetail();
            $vd->setFundHead($fundHead);
            $receiveVoucher->addVoucherDetail($vd);
        }

        $form = $this->createForm(ReceiveEntryForm::class, $receiveVoucher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $receiveVoucher->setStatus('draft');
            $this->receiveVoucherRepo()->prepareAndSave($receiveVoucher);

            $this->applyHiddenTransition($receiveVoucher, 'draft', 'draft');

            $this->addFlash('success', 'Receive Payment Created Successfully');



            return $this->redirectToRoute('account_sanction_receive_index');
        }

        return $this->render('@Account/ReceivePayment/create.html.twig', array(
            'receiveVoucher' => $receiveVoucher,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit/{id}", name="account_sanction_receive_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('edit:receive_voucher', receiveVoucher) and is_granted('SAME_OFFICE', receiveVoucher)")
     * @param Request $request
     * @param ReceiveVoucher $receiveVoucher
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, ReceiveVoucher $receiveVoucher)
    {
        $form = $this->createForm(ReceiveEntryForm::class, $receiveVoucher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $receiveVoucher->prepareAmount();
            $this->addFlash('success', 'Receive Payment Updated Successfully');

            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render('@Account/ReceivePayment/create.html.twig', array(
            'receiveVoucher' => $receiveVoucher,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/view/{id}", name="account_sanction_receive_view")
     * @Security("is_granted(['ROLE_ACCOUNT', 'DASB_USER']) and is_granted('SAME_OFFICE', receiveVoucher)")
     * @param ReceiveVoucher $receiveVoucher
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(ReceiveVoucher $receiveVoucher)
    {
        return $this->render('@Account/ReceivePayment/view.html.twig', array(
            'receiveVoucher' => $receiveVoucher,
        ));
    }
}
