<?php

namespace AccountBundle\Entity;

use AppBundle\Entity\AttachmentsTrait;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SanctionEntry
 *
 * @ORM\Table(name="account_sanction_register", uniqueConstraints={@ORM\UniqueConstraint(name="note_sheet_unique", columns={"note_sheet_generated_id","fund_type_id", "office_id", "year"})})
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\SanctionEntryRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="sanction_type", type="string")
 * @ORM\DiscriminatorMap({"base" = "SanctionEntry", "payment" = "SanctionPayment", "receive" = "SanctionReceive", "misc"="SanctionMiscellaneous"})
 */
class SanctionEntry extends BaseWorkflowEntity
{
    use AttachmentsTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="note_sheet_number", type="string", length=255)
     */
    private $noteSheetNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="note_sheet_generated_id", type="bigint")
     */
    private $noteSheetGeneratedId;

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
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\FundType")
     */
    private $fundType;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="voucher_date", type="datetime", nullable=true)
     */
    private $voucherDate;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getNoteSheetNumber();
    }

    /**
     * Set noteSheetNumber
     *
     * @param string $noteSheetNumber
     *
     * @return SanctionEntry
     */
    public function setNoteSheetNumber($noteSheetNumber)
    {
        $this->noteSheetNumber = $noteSheetNumber;

        return $this;
    }

    /**
     * Get noteSheetNumber
     *
     * @return string
     */
    public function getNoteSheetNumber()
    {
        return $this->noteSheetNumber;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return SanctionEntry
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
     * Set description
     *
     * @param string $description
     *
     * @return SanctionEntry
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
     * @param \stdClass $fundType
     *
     * @return SanctionEntry
     */
    public function setFundType($fundType)
    {
        $this->fundType = $fundType;

        return $this;
    }

    /**
     * Get account
     *
     * @return \stdClass
     */
    public function getFundType()
    {
        return $this->fundType;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return SanctionEntry
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
     * Set voucherDate
     *
     * @param \DateTime $voucherDate
     *
     * @return SanctionEntry
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
     * Set status
     *
     * @param string $status
     *
     * @return SanctionEntry
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getNoteSheetGeneratedId()
    {
        return $this->noteSheetGeneratedId;
    }

    /**
     * @param string $noteSheetGeneratedId
     *
     * @return SanctionEntry
     */
    public function setNoteSheetGeneratedId($noteSheetGeneratedId)
    {
        $this->noteSheetGeneratedId = $noteSheetGeneratedId;

        return $this;
    }

    public function getNoteSheetAndAmount()
    {
        return $this->noteSheetNumber . ' - ' . $this->amount;
    }
}

