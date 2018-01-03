<?php

namespace AccountBundle\Controller;

use AppBundle\Controller\BaseController;

/**
 * AccountBaseController controller.
 */
class AccountBaseController extends BaseController
{
    protected function getAccountManager()
    {
        //return $this->get(BudgetManager::class);
    }

    protected function getFundTypes()
    {
        return $this->isGranted("DASB_USER") ? $this->fundTypeRepo()->findBy(['basbFund' => false], ['sort' => 'asc']) : $this->fundTypeRepo()->findAll();
    }

    /**
     * @return \AccountBundle\Repository\FundTypeRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function fundTypeRepo()
    {
        return $this->getDoctrine()->getRepository('AccountBundle:FundType');
    }

    /**
     * @return \AccountBundle\Repository\FundHeadRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function fundHead()
    {
        return $this->getDoctrine()->getRepository('AccountBundle:FundHead');
    }

    /**
     * @return \AccountBundle\Repository\ChequeIssueRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function chequeIssueRepo()
    {
        return $this->getDoctrine()->getRepository('AccountBundle:ChequeIssue');
    }

    /**
     * @return \AccountBundle\Repository\PaymentVoucherRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function paymentVoucherRepo()
    {
        return $this->getDoctrine()->getRepository('AccountBundle:PaymentVoucher');
    }

    /**
     * @return \AccountBundle\Repository\SanctionEntryRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function sanctionEntryRepo()
    {
        return $this->getDoctrine()->getRepository('AccountBundle:SanctionEntry');
    }

    /**
     * @return \AccountBundle\Repository\SanctionPaymentRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function sanctionPaymentRepo()
    {
        return $this->getDoctrine()->getRepository('AccountBundle:SanctionPayment');
    }

    /**
     * @return \AccountBundle\Repository\SanctionReceiveRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function sanctionReceiveRepo()
    {
        return $this->getDoctrine()->getRepository('AccountBundle:SanctionReceive');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function sanctionAttachmentRepo()
    {
        return $this->getDoctrine()->getRepository('AccountBundle:SanctionAttachment');
    }

    /**
     * @return \AccountBundle\Repository\ReceiveVoucherRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function receiveVoucherRepo()
    {
        return $this->getDoctrine()->getRepository('AccountBundle:ReceiveVoucher');
    }

    /**
     * @return \AccountBundle\Repository\VoucherRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function voucherRepo()
    {
        return $this->getDoctrine()->getRepository('AccountBundle:Voucher');
    }

    /**
     * @return \AccountBundle\Repository\VoucherDetailRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function voucherDetailRepo()
    {
        return $this->getDoctrine()->getRepository('AccountBundle:VoucherDetail');
    }

    /**
     * @return \AccountBundle\Repository\BankAccountRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function bankAccountRepo()
    {
        return $this->getDoctrine()->getRepository('AccountBundle:BankAccount');
    }

    /**
     * @return \AccountBundle\Repository\ReconciliationRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function reconciliationRepo()
    {
        return $this->getDoctrine()->getRepository('AccountBundle:Reconciliation');
    }

    /**
     * @return \AccountBundle\Repository\ReconciliationRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function chequeReconciliationRepo()
    {
        return $this->getDoctrine()->getRepository('AccountBundle:ChequeReconciliation');
    }
}
