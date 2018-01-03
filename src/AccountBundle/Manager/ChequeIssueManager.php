<?php

namespace AccountBundle\Manager;

use AccountBundle\Entity\ChequeIssue;
use AccountBundle\Entity\FundType;
use AccountBundle\Entity\Voucher;
use AccountBundle\Entity\VoucherDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChequeIssueManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /** @var  TokenStorageInterface */
    protected $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->em = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }


    public function getFundHeadBalanceByFundType(FundType $fundType, $vouchers = [])
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $officeType = $user->getOffice()->getOfficeType();
        $payments = $this->em->getRepository('AccountBundle:PaymentVoucher')->paymentVoucherStats($fundType, $vouchers);
        $receives = $this->em->getRepository('AccountBundle:ReceiveVoucher')->receiveVoucherStats($fundType);
        $fundHeads = $this->em->getRepository('AccountBundle:FundHead')->findBy(['fundType' => $fundType, 'officeType' => $officeType], ['sort' => 'asc']);

        $data = [];
        foreach ($fundHeads as $fundHead) {
            $payment = isset($payments[$fundHead->getId()]) ? $payments[$fundHead->getId()] : 0;
            $receive = isset($receives[$fundHead->getId()]) ? $receives[$fundHead->getId()] : 0;

            $data[$fundHead->getId()] = [
                'name' => $fundHead->getName(),
                'balance' => $receive - $payment,
                'payment' => $payment,
                'receive' => $receive
            ];
        }

        return $data;
    }

    public function prepareVoucherDetail(ChequeIssue $chequeIssue)
    {
        /** @var Voucher $voucher */
        foreach ($chequeIssue->getVouchers() as $voucher) {
            /** @var VoucherDetail $vd */
            foreach ($voucher->getVoucherDetails() as $vd) {
                $voucher->vd[$vd->getFundHead()->getId()] = $vd->getAmount();
            }
        }
    }
}
