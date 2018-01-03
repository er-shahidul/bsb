<?php

namespace AccountBundle\Controller;

use AccountBundle\Datatables\MiscellaneousEntryDatatable;
use AccountBundle\Entity\FundType;
use AccountBundle\Entity\PaymentVoucher;
use AccountBundle\Entity\VoucherDetail;
use AccountBundle\Form\MiscellaneousEntryForm;
use AccountBundle\Manager\ChequeIssueManager;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * MiscellaneousEntryController controller.
 *
 * @Route("sanction/miscellaneous")
 */
class MiscellaneousEntryController extends AccountBaseController
{
    /**
     * @Route("/", name="account_sanction_miscellaneous_index")
     * @Method("GET")
     * @Security("has_role('ROLE_ACCOUNT') or is_granted('DASB_USER')")
     * @param Request $request
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $datatable = $this->prepareDatatable(MiscellaneousEntryDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("paymentvoucher.office = :office");
            $qb->setParameter('office', $this->getUser()->getOffice());

            $qb->andWhere($qb->expr()->isNull('paymentvoucher.chequeNumber'));
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@Account/MiscellaneousPayment/index.html.twig', array(
            'datatable' => $datatable,
            'fundTypes' => $this->getFundTypes(),
        ));
    }

    /**
     * @Route("/new/{fundType}", name="account_sanction_miscellaneous_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ACCOUNT_CLERK') or has_role('ROLE_DASB_CLERK')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, FundType $fundType)
    {
        $paymentVoucher = new PaymentVoucher();
        $paymentVoucher->setOffice($this->getOffice());
        $paymentVoucher->setFundType($fundType);
        $paymentVoucher->setReconciled(true);
        $paymentVoucher->setReconciliationDate(new \DateTime());

        foreach ($this->fundHead()->fundHeadByFundType($fundType, $this->getOffice()->getOfficeType()) as $fundHead) {
            $vd = new VoucherDetail();
            $vd->setFundHead($fundHead);
            $paymentVoucher->addVoucherDetail($vd);
        }

        $form = $this->createForm(MiscellaneousEntryForm::class, $paymentVoucher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->paymentVoucherRepo()->prepareAndSave($paymentVoucher);

            $this->applyHiddenTransition($paymentVoucher, 'draft', 'draft');

            $this->addFlash('success', 'Miscellaneous Payment Created Successfully');

            return $this->redirectToRoute('account_sanction_miscellaneous_index');
        }

        return $this->render('@Account/MiscellaneousPayment/create.html.twig', array(
            'paymentVoucher' => $paymentVoucher,
            'form' => $form->createView(),
            'fundHeadBalance' => $this->get(ChequeIssueManager::class)->getFundHeadBalanceByFundType($fundType)
        ));
    }

    /**
     * @Route("/edit/{id}", name="account_sanction_miscellaneous_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('edit:payment_voucher:draft', paymentVoucher) and is_granted('SAME_OFFICE', paymentVoucher)")
     * @param Request $request
     * @param PaymentVoucher $paymentVoucher
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, PaymentVoucher $paymentVoucher)
    {
        $form = $this->createForm(MiscellaneousEntryForm::class, $paymentVoucher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentVoucher->prepareAmount();

            $this->addFlash('success', 'Miscellaneous Payment Updated Successfully');

            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render('@Account/MiscellaneousPayment/create.html.twig', array(
            'paymentVoucher' => $paymentVoucher,
            'form' => $form->createView(),
            'fundHeadBalance' => $this->get(ChequeIssueManager::class)->getFundHeadBalanceByFundType($paymentVoucher->getFundType(), [$paymentVoucher])
        ));
    }

    /**
     * @Route("/view/{id}", name="account_sanction_miscellaneous_view")
     * @Security("is_granted(['ROLE_ACCOUNT_CLERK', 'DASB_USER']) and is_granted('SAME_OFFICE', paymentVoucher)")
     * @param PaymentVoucher $paymentVoucher
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @internal param PaymentVoucher $receiveVoucher
     */
    public function viewAction(PaymentVoucher $paymentVoucher)
    {
        return $this->render('@Account/MiscellaneousPayment/view.html.twig', array(
            'paymentVoucher' => $paymentVoucher,
        ));
    }
}
