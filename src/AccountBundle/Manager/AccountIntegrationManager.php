<?php

namespace AccountBundle\Manager;

use AccountBundle\Entity\AccountIntegration;
use AccountBundle\Mapper\Sanction;
use AccountBundle\Mapper\Voucher;
use AccountBundle\Mapper\VoucherDetail;
use Doctrine\ORM\EntityManagerInterface;

class AccountIntegrationManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param AccountIntegration $accountIntegration
     * @param Sanction $sanction
     * @param $postData
     */
    public function save($accountIntegration, $sanction, $postData, $formData)
    {
        /**
         * @var Voucher $voucher
         */
        foreach ($sanction->getVouchers() as $key => $voucher) {
            $voucher->setChequeNumber($postData['chequeNumber'][$key]);
            $voucher->setBankAccount($postData['bankAccount'][$key]);
            $voucher->setDescription($postData['description'][$key]);
            $vd = [];
            foreach ($postData['fundHead'] as $fundHeadId => $fundHead) {
                $vdp = new VoucherDetail();
                $vdp->setAmount($fundHead[$key]);
                $vdp->setFundHead($fundHeadId);
                $vd[$fundHeadId] = $vdp;
            }
            $voucher->setVoucherDetails($vd);
        }

        $accountIntegration->setData($sanction);
        $this->em->flush();
    }

    public function prepareVoucherDetail(Sanction $sanction)
    {
        /** @var Voucher $voucher */
        foreach ($sanction->getVouchers() as $voucher) {
            /** @var VoucherDetail $vd */
            foreach ($voucher->getVoucherDetails() as $vd) {
                $voucher->vd[$vd->getFundHead()] = $vd->getAmount();
            }
        }
    }
}