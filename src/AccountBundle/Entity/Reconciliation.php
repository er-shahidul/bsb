<?php

namespace AccountBundle\Entity;

use AppBundle\Entity\AttachmentsTrait;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reconciliation
 *
 * @ORM\Table(name="reconciliations")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\ReconciliationRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="reconciliation_type", type="string")
 * @ORM\DiscriminatorMap({"base" = "Reconciliation", "cheque_reconciliation" = "ChequeReconciliation", "cheque_return" = "ChequeReturn"})
 */
class Reconciliation extends BaseWorkflowEntity
{
    use AttachmentsTrait;
    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\FundType")
     */
    protected $fundType;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AccountBundle\Entity\Voucher", mappedBy="reconciliation")
     */
    protected $vouchers;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50)
     */
    protected $status = 'draft';

    /**
     * @var string
     *
     * @ORM\Column(name="reconcile_year", type="integer")
     */
    protected $year;

    /**
     * @var string
     *
     * @ORM\Column(name="reconcile_month", type="integer")
     */
    protected $month;

    public function __construct()
    {
        $this->vouchers = new ArrayCollection();
        $this->attachments = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getVouchers()
    {
        return $this->vouchers;
    }

    /**
     * @param ArrayCollection $vouchers
     *
     * @return Reconciliation
     */
    public function setVouchers($vouchers)
    {
        $this->vouchers = $vouchers;

        return $this;
    }

    public function addVoucher($voucher)
    {
        if (!$this->vouchers->contains($voucher)) {
            $this->vouchers->add($voucher);
        }
    }

    public function removeVoucher($voucher)
    {
        if ($this->vouchers->contains($voucher)) {
            $this->vouchers->remove($voucher);
        }
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Reconciliation
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
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
     * @return Reconciliation
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param string $year
     *
     * @return Reconciliation
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return string
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param string $month
     *
     * @return Reconciliation
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFundType()
    {
        return $this->fundType;
    }

    /**
     * @param mixed $fundType
     *
     * @return Reconciliation
     */
    public function setFundType($fundType)
    {
        $this->fundType = $fundType;

        return $this;
    }

    public function getMonthName()
    {
        return (new \DateTime($this->year.'-'.$this->month))->format('F Y');
    }
}