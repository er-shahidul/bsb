<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VoucherDetail
 *
 * @ORM\Table(name="voucher_details")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\VoucherDetailRepository")
 */
class VoucherDetail
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\FundHead")
     */
    private $fundHead;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount = 0;

    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\Voucher", inversedBy="voucherDetails", cascade={"persist"})
     */
    private $voucher;

    public function __toString()
    {
        return (string)$this->getId();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fundHead
     *
     * @param FundHead $fundHead
     *
     * @return VoucherDetail
     */
    public function setFundHead($fundHead)
    {
        $this->fundHead = $fundHead;

        return $this;
    }

    /**
     * Get fundHead
     *
     * @return FundHead
     */
    public function getFundHead()
    {
        return $this->fundHead;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return VoucherDetail
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set voucher
     *
     * @param Voucher $voucher
     *
     * @return VoucherDetail
     */
    public function setVoucher($voucher)
    {
        $this->voucher = $voucher;

        return $this;
    }

    /**
     * Get voucher
     *
     * @return \stdClass
     */
    public function getVoucher()
    {
        return $this->voucher;
    }
}

