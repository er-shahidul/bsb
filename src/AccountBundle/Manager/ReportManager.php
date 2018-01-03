<?php

namespace AccountBundle\Manager;

use AccountBundle\Entity\FundType;
use AccountBundle\Entity\Voucher;
use AccountBundle\Repository\VoucherRepository;
use AppBundle\Utility\DateUtil;
use Doctrine\ORM\EntityManagerInterface;

class ReportManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getPaymentData($params)
    {
        return $this->getLedgerData($params, $this->em->getRepository('AccountBundle:PaymentVoucher'));
    }

    public function getReceiveData($params)
    {
        return $this->getLedgerData($params, $this->em->getRepository('AccountBundle:ReceiveVoucher'));
    }

    /**
     * @param $params
     * @param $repo VoucherRepository
     * @return array
     */
    protected function getLedgerData($params, $repo)
    {
        $dateRange = DateUtil::getMonthDateRange($params['year'], $params['month']);
        $param = [
            'duration' => $dateRange,
            'office' => $params['office'],
            'fundType' => $params['fundType'],
            'voucherNumberIsNotNull' => true,
        ];
        $data = [
            'currentMonthTotal' => $repo->getVoucherTotal($param),
            'previousTotal' => $repo->getVoucherTotal([
                'duration' => ['end' => $dateRange['start']],
                'office' => $params['office'],
                'fundType' => $params['fundType'],
            ]),
            'vouchers' => $repo->getVouchers($param),
        ];

        $data['currentMonthGrandTotal'] = array_sum($data['currentMonthTotal']);
        $data['previousGrandTotal'] = array_sum($data['previousTotal']);

        return $data;
    }

    public function getBankReconciliationData($params)
    {
        $dateRange = DateUtil::getMonthDateRange($params['year'], $params['month']);
        $output = [];

        $param = [
            'duration'    => ['end' => $dateRange['end']],
            'office'      => $params['office'],
            'bankAccount' => $params['bankAccount'],
            'voucherNumberIsNotNull' => true,
        ];

        $payment = $this->em->getRepository('AccountBundle:PaymentVoucher')->getVoucherTotal($param, 'grandTotal');
        $receive = $this->em->getRepository('AccountBundle:ReceiveVoucher')->getVoucherTotal($param, 'grandTotal');

        $output['ledgerBalance'] = $receive - $payment;
        $output['nonReconciledVouchers'] = $this->em->getRepository('AccountBundle:PaymentVoucher')->getNonReconciledVouchers([
            'office' => $params['office'],
            'bankAccount' => $params['bankAccount'],
            'isReconciled' => false,
            'reconciledDuration' => ['start' => $dateRange['end']],
            'voucherNumberIsNotNull' => true,
        ]);

        $output['nonReconciledVouchersTotal'] = 0;
        foreach ($output['nonReconciledVouchers'] as $voucher) {
            $output['nonReconciledVouchersTotal'] += $voucher->getAmount();
        }

        $output['bankBalance'] = $output['ledgerBalance'];

        return $output;
    }

    public function getVoucherReportData($data, $type = 'receive')
    {
        $fundType = $this->em->getReference(FundType::class, $data['formData']['fundType']);
        /** @var VoucherRepository $repo */
        $repo = $type == 'receive' ? $this->em->getRepository('AccountBundle:ReceiveVoucher') : $this->em->getRepository('AccountBundle:PaymentVoucher');
        $qb = $repo->getVoucherQuery([
            'duration' => [
                'start' => $data['formData']['startDate'],
                'end' => $data['formData']['endDate'],
            ],
            'fundType' => $fundType,
            'voucherNumberIsNotNull' => true,
        ]);
        $qb->andWhere($qb->expr()->eq('voucher.toOrFrom', ':toOrFrom'))->setParameter('toOrFrom', $data['formData']['toOrFrom']);
        $qb->andWhere($qb->expr()->in('voucher.against', ':against'))->setParameter('against', $data['formData']['against']);
        $qb->orderBy('voucher.voucherDate', 'DESC');

        return $this->prepareFundReceiveData($qb->getQuery()->getResult());
    }

    protected function prepareFundReceiveData($result)
    {
        $data = [];
        $subTotal = [];
        $total = 0;
        /** @var Voucher $voucher */
        foreach ($result as $voucher) {
            $data[$voucher->getAgainst()][] = $voucher;

            if (!isset($subTotal[$voucher->getAgainst()])) {
                $subTotal[$voucher->getAgainst()] = 0;
            }

            $subTotal[$voucher->getAgainst()] += $voucher->getAmount();
            $total += $voucher->getAmount();
        }

        return [
            'vouchers' => $data,
            'vouchersSubTotal' => $subTotal,
            'vouchersTotal' => $total
        ];
    }
}