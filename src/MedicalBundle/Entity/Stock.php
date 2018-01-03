<?php

namespace MedicalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 *
 * @ORM\Table(name="medical_stocks")
 * @ORM\Entity(repositoryClass="MedicalBundle\Repository\StockRepository")
 */
class Stock
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
     * @ORM\Column(name="balance", type="integer")
     */
    private $balance = 0;


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
     * @return Stock
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
     * @return Stock
     */
    public function setMedicine($medicine)
    {
        $this->medicine = $medicine;

        return $this;
    }

    /**
     * Get medicine
     * @return Medicine
     */
    public function getMedicine()
    {
        return $this->medicine;
    }

    /**
     * Set balance
     *
     * @param \stdClass $balance
     *
     * @return Stock
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }
}

