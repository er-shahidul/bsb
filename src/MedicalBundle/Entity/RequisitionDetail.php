<?php

namespace MedicalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RequisitionDetail
 *
 * @ORM\Table(name="medical_requisition_details")
 * @ORM\Entity(repositoryClass="MedicalBundle\Repository\RequisitionDetailRepository")
 */
class RequisitionDetail
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
     * @var Requisition
     *
     * @ORM\ManyToOne(targetEntity="MedicalBundle\Entity\Requisition", inversedBy="requisitionDetails")
     */
    private $requisition;

    /**
     * @var Medicine
     *
     * @ORM\ManyToOne(targetEntity="MedicalBundle\Entity\Medicine")
     */
    private $medicine;

    /**
     * @var int
     *
     * @ORM\Column(name="request_amount", type="integer")
     */
    private $requestAmount = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="sanction_amount", type="integer")
     */
    private $sanctionAmount = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="receive_amount", type="integer")
     */
    private $receiveAmount = 0;

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
     * Set requisition
     *
     * @param Requisition $requisition
     *
     * @return RequisitionDetail
     */
    public function setRequisition($requisition)
    {
        $this->requisition = $requisition;

        return $this;
    }

    /**
     * Get requisition
     *
     * @return Requisition
     */
    public function getRequisition()
    {
        return $this->requisition;
    }

    /**
     * Set medicine
     *
     * @param \stdClass $medicine
     *
     * @return RequisitionDetail
     */
    public function setMedicine($medicine)
    {
        $this->medicine = $medicine;

        return $this;
    }

    /**
     * Get medicine
     *
     * @return Medicine
     */
    public function getMedicine()
    {
        return $this->medicine;
    }

    /**
     * Set requestAmount
     *
     * @param integer $requestAmount
     *
     * @return RequisitionDetail
     */
    public function setRequestAmount($requestAmount)
    {
        $this->requestAmount = $requestAmount;

        return $this;
    }

    /**
     * Get requestAmount
     *
     * @return int
     */
    public function getRequestAmount()
    {
        return $this->requestAmount;
    }

    /**
     * @return int
     */
    public function getSanctionAmount()
    {
        return $this->sanctionAmount;
    }

    /**
     * @param int $sanctionAmount
     *
     * @return RequisitionDetail
     */
    public function setSanctionAmount($sanctionAmount)
    {
        $this->sanctionAmount = $sanctionAmount;

        return $this;
    }

    /**
     * @return int
     */
    public function getReceiveAmount()
    {
        return $this->receiveAmount;
    }

    /**
     * @param int $receiveAmount
     *
     * @return RequisitionDetail
     */
    public function setReceiveAmount($receiveAmount)
    {
        $this->receiveAmount = $receiveAmount;

        return $this;
    }
}

