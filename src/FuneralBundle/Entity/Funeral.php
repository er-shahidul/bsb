<?php

namespace FuneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\AttachmentsTrait;
use PersonnelBundle\Entity\ExServiceman;
use Doctrine\Common\Collections\ArrayCollection;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;

/**
 * Funeral
 *
 * @ORM\Table(name="funerals")
 * @ORM\Entity(repositoryClass="FuneralBundle\Repository\FuneralRepository")
 */
class Funeral extends BaseWorkflowEntity
{
    use AttachmentsTrait;

    /**
     * @var ExServiceman
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ExServiceman")
     * @ORM\JoinColumn(name="ex_serviceman")
     */
    private $exServiceman;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deceased_date", type="date", nullable=true)
     */
    private $deceasedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="deceased_place", type="string", length=255, nullable=true)
     */
    private $deceasedPlace;

    /**
     * @var string
     *
     * @ORM\Column(name="deceased_reason", type="string", length=512, nullable=true)
     */
    private $deceasedReason;

    /**
     * @var string
     *
     * @ORM\Column(name="base_area_unit", type="string", length=255, nullable=true)
     */
    private $baseAreaUnit;

    /**
     * @var string
     *
     * @ORM\Column(name="expenditure", type="string", length=512, nullable=true)
     */
    private $expenditure;

    /**
     * @var string
     *
     * @ORM\Column(name="burial_place", type="string", length=255, nullable=true)
     */
    private $burialPlace;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="string", length=255, nullable=true)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    /**
     * @return ExServiceman
     */
    public function getExServiceman()
    {
        return $this->exServiceman;
    }

    /**
     * @param ExServiceman $exServiceman
     */
    public function setExServiceman($exServiceman)
    {
        $this->exServiceman = $exServiceman;
    }

    /**
     * @return \DateTime
     */
    public function getDeceasedDate()
    {
        return $this->deceasedDate;
    }

    /**
     * @param \DateTime $deceasedDate
     */
    public function setDeceasedDate($deceasedDate)
    {
        $this->deceasedDate = $deceasedDate;
    }

    /**
     * @return string
     */
    public function getDeceasedPlace()
    {
        return $this->deceasedPlace;
    }

    /**
     * @param string $deceasedPlace
     */
    public function setDeceasedPlace($deceasedPlace)
    {
        $this->deceasedPlace = $deceasedPlace;
    }

    /**
     * @return string
     */
    public function getDeceasedReason()
    {
        return $this->deceasedReason;
    }

    /**
     * @param string $deceasedReason
     */
    public function setDeceasedReason($deceasedReason)
    {
        $this->deceasedReason = $deceasedReason;
    }

    /**
     * @return string
     */
    public function getBaseAreaUnit()
    {
        return $this->baseAreaUnit;
    }

    /**
     * @param string $baseAreaUnit
     */
    public function setBaseAreaUnit($baseAreaUnit)
    {
        $this->baseAreaUnit = $baseAreaUnit;
    }

    /**
     * @return string
     */
    public function getExpenditure()
    {
        return $this->expenditure;
    }

    /**
     * @param string $expenditure
     */
    public function setExpenditure($expenditure)
    {
        $this->expenditure = $expenditure;
    }

    /**
     * @return string
     */
    public function getBurialPlace()
    {
        return $this->burialPlace;
    }

    /**
     * @param string $burialPlace
     */
    public function setBurialPlace($burialPlace)
    {
        $this->burialPlace = $burialPlace;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getFuneralCondition()
    {
        if($this->status == "rejected"){
            return false;
        }
        return true;
    }
}

