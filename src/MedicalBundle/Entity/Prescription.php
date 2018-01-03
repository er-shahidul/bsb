<?php

namespace MedicalBundle\Entity;

use AppBundle\Traits\BlameableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use PersonnelBundle\Entity\ExServiceman;

/**
 * Prescription
 *
 * @ORM\Table(name="medical_prescriptions")
 * @ORM\Entity(repositoryClass="MedicalBundle\Repository\PrescriptionRepository")
 */
class Prescription
{
    use BlameableEntity, TimestampableEntity;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Dispensary
     *
     * @ORM\ManyToOne(targetEntity="MedicalBundle\Entity\Dispensary")
     */
    private $dispensary;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="date")
     */
    private $createDate;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="MedicalBundle\Entity\Inventory")
     * @ORM\JoinTable(name="medical_join_prescription_inventories",
     *      joinColumns={@ORM\JoinColumn(name="prescription_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="inventory_id", referencedColumnName="id", unique=true)}
     *   )
     */
    private $inventories;

    /**
     * @var string
     *
     * @ORM\Column(name="remarks", type="text", nullable=true)
     */
    private $remarks;

    /**
     * @var ExServiceman
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ExServiceman")
     */
    private $serviceMan;

    public function __construct()
    {
        $this->inventories = new ArrayCollection();
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
     * Set dispensary
     *
     * @param \stdClass $dispensary
     *
     * @return Prescription
     */
    public function setDispensary($dispensary)
    {
        $this->dispensary = $dispensary;

        return $this;
    }

    /**
     * Get dispensary
     * @return Dispensary
     */
    public function getDispensary()
    {
        return $this->dispensary;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Prescription
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set inventories
     *
     * @param array $inventories
     *
     * @return Prescription
     */
    public function setInventories($inventories)
    {
        $this->inventories = $inventories;

        return $this;
    }

    /**
     * Get inventories
     *
     * @return array
     */
    public function getInventories()
    {
        return $this->inventories;
    }

    /**
     * Set remarks
     *
     * @param string $remarks
     *
     * @return Prescription
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;

        return $this;
    }

    /**
     * Get remarks
     *
     * @return string
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Set serviceMan
     *
     * @param \stdClass $serviceMan
     *
     * @return Prescription
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
}

