<?php

namespace AccountBundle\Entity;

use AccountBundle\Mapper\Sanction;
use AccountBundle\Util\SerializeHelper;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * AccountIntegration
 *
 * @ORM\Table(name="account_integration")
 * @ORM\Entity()
 */
class AccountIntegration extends BaseWorkflowEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50)
     */
    protected $status = 'draft';

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="text")
     */
    protected $data;

    protected $serializer;

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Sanction|null
     */
    public function getData()
    {
        $sanction = SerializeHelper::deserialize($this->data, Sanction::class);
        $vouchers = [];
        foreach ($sanction->getVouchers() as $voucher) {
            $voucher = SerializeHelper::deserialize(json_encode($voucher), \AccountBundle\Mapper\Voucher::class);
            $vd = [];
            foreach ($voucher->getVoucherDetails() as $voucherDetail) {
                $vd[] = SerializeHelper::deserialize(json_encode($voucherDetail), \AccountBundle\Mapper\VoucherDetail::class);
            }
            $voucher->setVoucherDetails($vd);
            $vouchers[] = $voucher;
        }

        $sanction->setVouchers($vouchers);
        return $sanction;
    }

    public function setData($entity)
    {
        $this->data = SerializeHelper::serialize($entity);
    }

    public function getSerializeData()
    {
        return $this->data;
    }
}