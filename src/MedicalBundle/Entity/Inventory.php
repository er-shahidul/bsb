<?php

namespace MedicalBundle\Entity;

use AppBundle\Traits\BlameableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Inventory
 *
 * @ORM\Table(name="medical_inventories")
 * @ORM\Entity(repositoryClass="MedicalBundle\Repository\InventoryRepository")
 */
class Inventory
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
     * @var Medicine
     *
     * @ORM\ManyToOne(targetEntity="MedicalBundle\Entity\Medicine")
     */
    private $medicine;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="date")
     */
    private $createDate;

    /**
     * @var string
     *
     * @ORM\Column(name="remarks", type="text", nullable=true)
     */
    private $remarks;


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
     * @param string $dispensary
     *
     * @return Inventory
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
     * Set medicine
     *
     * @param string $medicine
     *
     * @return Inventory
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Inventory
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Inventory
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
     * Set remarks
     *
     * @param string $remarks
     *
     * @return Inventory
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
}

