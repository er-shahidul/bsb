<?php

namespace AccountBundle\Entity;

use AppBundle\Entity\AttachmentsTrait;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Voucher
 *
 * @ORM\Table(name="vouchers")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\VoucherRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="voucher_type", type="string")
 * @ORM\DiscriminatorMap({"base" = "Voucher", "payment" = "PaymentVoucher", "receive" = "ReceiveVoucher"})
 */
class Voucher extends BaseWorkflowEntity
{
    use AttachmentsTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="to_or_from", type="string", length=255)
     */
    private $toOrFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="against", type="string", length=255)
     */
    private $against;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\BankAccount")
     */
    private $account;

    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\FundType")
     */
    private $fundType;

    /**
     * @var int
     *
     * @ORM\Column(name="voucherNumber", type="string", length=20, nullable=true)
     */
    private $voucherNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="generated_voucher_number", type="integer", nullable=true)
     */
    private $generatedVoucherNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="voucherDate", type="date", nullable=true)
     */
    private $voucherDate;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="chequeNumber", type="string", length=255, nullable=true)
     */
    private $chequeNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="chequeDate", type="date", nullable=true)
     */
    private $chequeDate;

    /**
     * @ORM\ManyToMany(targetEntity="AccountBundle\Entity\SanctionEntry")
     */
    private $sanctions;

    /**
     * @ORM\OneToMany(targetEntity="AccountBundle\Entity\VoucherDetail", mappedBy="voucher",cascade={"persist"})
     */
    private $voucherDetails;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status = 'draft';

    /**
     * @var boolean
     * @ORM\Column(name="is_debited", type="boolean")
     */
    private $debited = false;

    /**
     * @var boolean
     * @ORM\Column(name="is_reconciled", type="boolean")
     */
    private $reconciled = false;

    /**
     * @var boolean
     * This field use for as checked in reconciliation form
     * @ORM\Column(name="mark_for_reconcile", type="boolean")
     */
    private $markForReconcile = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reconciliation_date", type="date", nullable=true)
     */
    private $reconciliationDate;

    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\Reconciliation", inversedBy="vouchers")
     */
    private $reconciliation;

    public function __construct()
    {
        $this->voucherDetails = new ArrayCollection();
        $this->year = date('Y');
        $this->attachments = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->getId();
    }

    /**
     * Set paymentTo
     *
     * @param string $toOrFrom
     *
     * @return Voucher
     */
    public function setToOrFrom($toOrFrom)
    {
        $this->toOrFrom = $toOrFrom;

        return $this;
    }

    /**
     * Get paymentTo
     *
     * @return string
     */
    public function getToOrFrom()
    {
        return $this->toOrFrom;
    }

    /**
     * Set paymentAgainst
     *
     * @param string $against
     *
     * @return Voucher
     */
    public function setAgainst($against)
    {
        $this->against = $against;

        return $this;
    }

    /**
     * Get paymentAgainst
     *
     * @return string
     */
    public function getAgainst()
    {
        return $this->against;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return Voucher
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
     * Set voucherDate
     *
     * @param \DateTime $voucherDate
     *
     * @return Voucher
     */
    public function setVoucherDate($voucherDate)
    {
        $this->voucherDate = $voucherDate;

        return $this;
    }

    /**
     * Get voucherDate
     *
     * @return \DateTime
     */
    public function getVoucherDate()
    {
        return $this->voucherDate;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Voucher
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set account
     *
     * @param \stdClass $account
     *
     * @return Voucher
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \stdClass
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set fundType
     *
     * @param  $fundType
     *
     * @return Voucher
     */
    public function setFundType($fundType)
    {
        $this->fundType = $fundType;

        return $this;
    }

    /**
     * Get fundType
     *
     * @return FundType
     */
    public function getFundType()
    {
        return $this->fundType;
    }

    /**
     * Set voucherNumber
     *
     * @param integer $voucherNumber
     *
     * @return Voucher
     */
    public function setVoucherNumber($voucherNumber)
    {
        $this->voucherNumber = $voucherNumber;

        return $this;
    }

    /**
     * Get voucherNumber
     *
     * @return int
     */
    public function getVoucherNumber()
    {
        return $this->voucherNumber;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Voucher
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set chequeNumber
     *
     * @param string $chequeNumber
     *
     * @return Voucher
     */
    public function setChequeNumber($chequeNumber)
    {
        $this->chequeNumber = $chequeNumber;

        return $this;
    }

    /**
     * Get chequeNumber
     *
     * @return string
     */
    public function getChequeNumber()
    {
        return $this->chequeNumber;
    }

    /**
     * Set sanctions
     *
     * @param ArrayCollection $sanctions
     *
     * @return Voucher
     */
    public function setSanctions($sanctions)
    {
        $this->sanctions = $sanctions;

        return $this;
    }

    /**
     * Get sanctions
     *
     * @return \stdClass
     */
    public function getSanctions()
    {
        return $this->sanctions;
    }

    /**
     * Set voucherDetails
     *
     * @param \stdClass $voucherDetails
     *
     * @return Voucher
     */
    public function setVoucherDetails($voucherDetails)
    {
        $this->voucherDetails = $voucherDetails;

        return $this;
    }

    /**
     * Get voucherDetails
     *
     * @return ArrayCollection
     */
    public function getVoucherDetails()
    {
        return $this->voucherDetails;
    }

    /**
     * Set chequeDate
     *
     * @param \DateTime $chequeDate
     *
     * @return Voucher
     */
    public function setChequeDate($chequeDate)
    {
        $this->chequeDate = $chequeDate;

        return $this;
    }

    /**
     * Get chequeDate
     *
     * @return \DateTime
     */
    public function getChequeDate()
    {
        return $this->chequeDate;
    }

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
     * @return Voucher
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function addVoucherDetail($voucherDetail)
    {
        if (!$this->voucherDetails->contains($voucherDetail)) {
            $this->voucherDetails->add($voucherDetail);
        }
    }

    public function removeVoucherDetail($voucherDetail)
    {
        if ($this->voucherDetails->contains($voucherDetail)) {
            $this->voucherDetails->remove($voucherDetail);
        }
    }

    public function prepareAmount()
    {
        $total = 0;
        foreach ($this->voucherDetails as $detail) {
            $total += $detail->getAmount();
        }

        $this->setAmount($total);
    }

    /**
     * @return bool
     */
    public function isReconciled()
    {
        return $this->reconciled;
    }

    /**
     * @return bool
     */
    public function getReconciled()
    {
        return $this->reconciled;
    }

    /**
     * @param bool $reconciled
     *
     * @return Voucher
     */
    public function setReconciled($reconciled)
    {
        $this->reconciled = $reconciled;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getReconciliationDate()
    {
        return $this->reconciliationDate;
    }

    /**
     * @param \DateTime $reconciliationDate
     *
     * @return Voucher
     */
    public function setReconciliationDate($reconciliationDate)
    {
        $this->reconciliationDate = $reconciliationDate;

        return $this;
    }

    public function skipInitialQueue()
    {
        return true;
    }

    /**
     * @return int
     */
    public function getGeneratedVoucherNumber()
    {
        return $this->generatedVoucherNumber;
    }

    /**
     * @param int $generatedVoucherNumber
     *
     * @return Voucher
     */
    public function setGeneratedVoucherNumber($generatedVoucherNumber)
    {
        $this->generatedVoucherNumber = $generatedVoucherNumber;

        return $this;
    }

    /**
     * @return Reconciliation
     */
    public function getReconciliation()
    {
        return $this->reconciliation;
    }

    /**
     * @param mixed $reconciliation
     *
     * @return Voucher
     */
    public function setReconciliation($reconciliation)
    {
        $this->reconciliation = $reconciliation;

        return $this;
    }

    /**
     * @return bool
     */
    public function getDebited()
    {
        return $this->debited;
    }

    /**
     * @param bool $debited
     *
     * @return Voucher
     */
    public function setDebited($debited)
    {
        $this->debited = $debited;

        return $this;
    }

    /**
     * @return bool
     */
    public function getMarkForReconcile()
    {
        return $this->markForReconcile;
    }

    /**
     * @param bool $markForReconcile
     *
     * @return Voucher
     */
    public function setMarkForReconcile($markForReconcile)
    {
        $this->markForReconcile = $markForReconcile;

        return $this;
    }

    public function formatVoucherNumber($number, $prefix)
    {
        $voucherNumber = str_pad($number, 3, 0, STR_PAD_LEFT);
        return sprintf('%s-%s', $prefix, $voucherNumber);
    }
}