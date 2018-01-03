<?php

namespace MedicalBundle\Entity;

use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Requisition
 *
 * @ORM\Table(name="medical_requisitions")
 * @ORM\Entity(repositoryClass="MedicalBundle\Repository\RequisitionRepository")
 */
class Requisition extends BaseWorkflowEntity
{
    /**
     * @var Dispensary
     *
     * @ORM\ManyToOne(targetEntity="MedicalBundle\Entity\Dispensary", inversedBy="requisitions")
     */
    private $dispensary;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MedicalBundle\Entity\RequisitionDetail", mappedBy="requisition")
     */
    private $requisitionDetails;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="text", length=255, nullable=true)
     */
    private $status;

    public function __construct()
    {
        $this->requisitionDetails = new ArrayCollection();
    }

    /**
     * Set dispensary
     *
     * @param mixed $dispensary
     *
     * @return Requisition
     */
    public function setDispensary($dispensary)
    {
        $this->dispensary = $dispensary;

        return $this;
    }

    /**
     * Get dispensary
     *
     * @return Dispensary
     */
    public function getDispensary()
    {
        return $this->dispensary;
    }

    /**
     * @return ArrayCollection
     */
    public function getRequisitionDetails()
    {
        return $this->requisitionDetails;
    }

    /**
     * @param ArrayCollection $requisitionDetails
     *
     * @return Requisition
     */
    public function setRequisitionDetails($requisitionDetails)
    {
        $this->requisitionDetails = $requisitionDetails;

        return $this;
    }

    public function addRequisitionDetail($requisitionDetail)
    {
        if (!$this->requisitionDetails->contains($requisitionDetail)) {
            $this->requisitionDetails->add($requisitionDetail);
        }
    }

    public function removeRequisitionDetail($requisitionDetail)
    {
        if ($this->requisitionDetails->contains($requisitionDetail)) {
            $this->requisitionDetails->remove($requisitionDetail);
        }
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
     * @return Requisition
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}

