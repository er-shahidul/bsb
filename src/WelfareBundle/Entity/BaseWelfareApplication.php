<?php

namespace WelfareBundle\Entity;

use AppBundle\Entity\AttachmentsTrait;
use BoardMeetingBundle\Core\BoardMeetingRelationTrait;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use PersonnelBundle\Entity\ExFamilyInformation;
use PersonnelBundle\Entity\ExServiceman;

/**
 * BaseWelfareApplication
 *
 * @ORM\MappedSuperclass
 */
abstract class BaseWelfareApplication extends BaseWorkflowEntity
{
    use AttachmentsTrait;

    /**
     * @var ExServiceman
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ExServiceman")
     */
    private $serviceMan;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="grantStatus", type="string", length=255, nullable=true)
     */
    private $grantStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="granted_at", type="datetime", nullable=true)
     */
    protected $grantedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="applicant", type="string", length=255)
     */
    private $applicant;

    /**
     * @var bool
     *
     * @ORM\Column(name="portalApplication", type="boolean")
     */
    private $portalApplication = false;

    /**
     * @var ExFamilyInformation
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ExFamilyInformation")
     */
    private $applicantDependOn;

    /**
     * @var String
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var array
     *
     * @ORM\Column(name="application_data", type="json_array", nullable=true)
     */
    private $applicationData;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    /**
     * Set serviceMan
     *
     * @param ExServiceman $serviceMan
     *
     * @return WelfareApplication
     */
    public function setServiceMan($serviceMan)
    {
        $this->serviceMan = $serviceMan;

        return $this;
    }

    /**
     * Get serviceMan
     *
     * @return ExServiceman
     */
    public function getServiceMan()
    {
        return $this->serviceMan;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return WelfareApplication
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
     * Set grantStatus
     *
     * @param string $grantStatus
     *
     * @return WelfareApplication
     */
    public function setGrantStatus($grantStatus)
    {
        $this->grantStatus = $grantStatus;

        return $this;
    }

    /**
     * Get grantStatus
     *
     * @return string
     */
    public function getGrantStatus()
    {
        return $this->grantStatus;
    }

    /**
     * Set applicant
     *
     * @param string $applicant
     *
     * @return WelfareApplication
     */
    public function setApplicant($applicant)
    {
        $this->applicant = $applicant;

        return $this;
    }

    /**
     * Get applicant
     *
     * @return string
     */
    public function getApplicant()
    {
        return $this->applicant;
    }

    /**
     * Set portalApplication
     *
     * @param boolean $portalApplication
     *
     * @return WelfareApplication
     */
    public function setPortalApplication($portalApplication)
    {
        $this->portalApplication = $portalApplication;

        return $this;
    }

    /**
     * Get portalApplication
     *
     * @return bool
     */
    public function getPortalApplication()
    {
        return $this->portalApplication;
    }

    /**
     * @return String
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param String $note
     *
     * @return WelfareApplication
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return ExFamilyInformation
     */
    public function getApplicantDependOn()
    {
        return $this->applicantDependOn;
    }

    /**
     * @param ExFamilyInformation $applicantDependOn
     *
     * @return WelfareApplication
     */
    public function setApplicantDependOn($applicantDependOn)
    {
        $this->applicantDependOn = $applicantDependOn;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return \DateTime
     */
    public function getGrantedAt()
    {
        return $this->grantedAt;
    }

    /**
     * @param \DateTime $grantedAt
     */
    public function setGrantedAt($grantedAt)
    {
        $this->grantedAt = $grantedAt;
    }

    /**
     * @return mixed
     */
    public function getApplicationData()
    {
        return $this->applicationData;
    }

    /**
     * @param mixed $applicationData
     */
    public function setApplicationData($applicationData)
    {
        $this->applicationData = $applicationData;
    }
}

