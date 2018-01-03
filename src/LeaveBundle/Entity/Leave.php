<?php

namespace LeaveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\AttachmentsTrait;
use PersonnelBundle\Entity\ServingPersonnel;
use Doctrine\Common\Collections\ArrayCollection;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;

/**
 * Leave
 *
 * @ORM\Table(name="leaves")
 * @ORM\Entity(repositoryClass="LeaveBundle\Repository\LeaveRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="leave_type", type="string")
 * @ORM\DiscriminatorMap({"base" = "Leave", "director" = "DirectorLeave", "general" = "GeneralLeave", "secretary" = "SecretaryLeave", "civilian" = "CivilianLeave", "military" = "MilitaryLeave"})
 */
class Leave extends BaseWorkflowEntity
{
    use AttachmentsTrait;

    /**
     * @var ServingPersonnel
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ServingPersonnel")
     */
    protected $identityNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    protected $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime")
     */
    protected $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="subjects", type="string", length=255, nullable=true)
     */
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="number_of_date", type="string", length=255, nullable=true)
     */
    protected $numberOfDate;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="string", length=512, nullable=true)
     */
    protected $details;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="type_of_leave", type="string", length=255, nullable=true)
     */
    protected $typeOfLeave;

    function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    public function getType()
    {
        return 'base';
    }

    /**
     * @return mixed
     */
    public function getIdentityNumber()
    {
        return $this->identityNumber;
    }

    /**
     * @param mixed $identityNumber
     */
    public function setIdentityNumber($identityNumber)
    {
        $this->identityNumber = $identityNumber;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getNumberOfDate()
    {
        return $this->numberOfDate;
    }

    /**
     * @param string $numberOfDate
     */
    public function setNumberOfDate($numberOfDate)
    {
        $this->numberOfDate = $numberOfDate;
    }

    /**
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param string $details
     */
    public function setDetails($details)
    {
        $this->details = $details;
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

    /**
     * @return string
     */
    public function getTypeOfLeave()
    {
        return $this->typeOfLeave;
    }

    /**
     * @param string $typeOfLeave
     */
    public function setTypeOfLeave($typeOfLeave)
    {
        $this->typeOfLeave = $typeOfLeave;
    }
}

