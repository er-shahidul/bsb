<?php

namespace AccountBundle\Entity;

use AppBundle\Entity\AttachmentsTrait;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ChequeIssue
 *
 * @ORM\Table(name="cheque_issue")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\ChequeIssueRepository")
 */
class ChequeIssue extends BaseWorkflowEntity
{
    use AttachmentsTrait;

    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\FundType")
     */
    private $fundType;

    /**
     * @ORM\ManyToMany(targetEntity="AccountBundle\Entity\SanctionEntry")
     */
    private $sanctions;

    /**
     * @ORM\ManyToMany(targetEntity="AccountBundle\Entity\Voucher")
     */
    private $vouchers;

    /**
     * @ORM\Column(name="cheque_date", type="datetime", nullable=true)
     */
    private $chequeDate;

    /**
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status = 'draft';

    public function __construct()
    {
        $this->vouchers = new ArrayCollection();
        $this->sanctions = new ArrayCollection();
        $this->attachments = new ArrayCollection();
    }

    /**
     * Set sanctions
     *
     * @param ArrayCollection $sanctions
     *
     * @return ChequeIssue
     */
    public function setSanctions($sanctions)
    {
        $this->sanctions = $sanctions;

        return $this;
    }

    /**
     * Get sanctions
     *
     * @return ArrayCollection
     */
    public function getSanctions()
    {
        return $this->sanctions;
    }

    /**
     * @param SanctionEntry $sanctionEntry
     */
    public function addSanction(SanctionEntry $sanctionEntry)
    {
        if (!$this->sanctions->contains($sanctionEntry)) {
            $this->sanctions->add($sanctionEntry);
        }
    }

    /**
     * @param $sanctionEntry $sanctionEntry
     */
    public function removeSanction(SanctionEntry $sanctionEntry)
    {
        if ($this->sanctions->contains($sanctionEntry)) {
            $this->sanctions->remove($sanctionEntry);
        }
    }

    /**
     * Set vouchers
     *
     * @param \stdClass $vouchers
     *
     * @return ChequeIssue
     */
    public function setVouchers($vouchers)
    {
        $this->vouchers = $vouchers;

        return $this;
    }

    /**
     * Get vouchers
     *
     * @return ArrayCollection
     */
    public function getVouchers()
    {
        return $this->vouchers;
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
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     *
     * @return ChequeIssue
     */
    public function setStatus($status)
    {
        $this->status = $status;

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
     * @return ChequeIssue
     */
    public function setFundType($fundType)
    {
        $this->fundType = $fundType;

        return $this;
    }

    public function getNoteSheetTotalAmount()
    {
        $total = 0;

        foreach ($this->sanctions as $sanction) {
            $total += $sanction->getAmount();
        }

        return $total;
    }

    public function getVoucherTotal()
    {
        $total = 0;

        foreach ($this->vouchers as $voucher) {
            $total += $voucher->getAmount();
        }

        return $total;
    }

    /**
     * @return mixed
     */
    public function getChequeDate()
    {
        return $this->chequeDate;
    }

    /**
     * @param mixed $chequeDate
     *
     * @return ChequeIssue
     */
    public function setChequeDate($chequeDate)
    {
        $this->chequeDate = $chequeDate;

        return $this;
    }
}

