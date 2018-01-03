<?php

namespace AccountBundle\Controller;

use AccountBundle\Datatables\PaymentVouchersDatatable;
use AccountBundle\Entity\PaymentVoucher;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * PaymentVouchersController controller.
 *
 * @Route("cheque-issue/vouchers")
 */
class PaymentVouchersController extends AccountBaseController
{
    /**
     * @Route("/", name="account_payment_vouchers_index")
     * @Method("GET")
     * @Security("has_role('ROLE_ACCOUNT') or is_granted('DASB_USER')")
     * @param Request $request
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $datatable = $this->prepareDatatable(PaymentVouchersDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("paymentvoucher.office = :office");
            $qb->setParameter('office', $this->getUser()->getOffice());

            $qb->andWhere($qb->expr()->isNotNull('paymentvoucher.chequeNumber'));
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@Account/PaymentVouchers/index.html.twig', array(
            'datatable' => $datatable,
            'fundTypes' => $this->getFundTypes(),
        ));
    }

    /**
     * @Route("/view/{id}", name="account_payment_vouchers_view")
     * @Security("is_granted(['ROLE_ACCOUNT_CLERK', 'DASB_USER']) and is_granted('SAME_OFFICE', paymentVoucher)")
     * @param PaymentVoucher $paymentVoucher
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @internal param PaymentVoucher $receiveVoucher
     */
    public function viewAction(PaymentVoucher $paymentVoucher)
    {
        return $this->render('@Account/PaymentVouchers/view.html.twig', array(
            'paymentVoucher' => $paymentVoucher,
        ));
    }
}
