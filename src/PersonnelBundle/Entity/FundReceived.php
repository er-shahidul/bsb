<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FundReceived
 *
 * @ORM\Table(name="fund_received")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\FundReceivedRepository")
 */
class FundReceived
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
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var WelfareFund
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\WelfareFund")
     */
    private $fundType;

    /**
     * @var ExServiceman
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ExServiceman", inversedBy="receivedFunds", cascade={"persist"})
     */
    private $serviceman;

    /**
     * @var bool
     *
     * @ORM\Column(name="system_generated", type="boolean", nullable=true)
     */
    private $systemGenerated = FALSE;

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
     * Set amount
     *
     * @param float $amount
     *
     * @return FundReceived
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return FundReceived
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set fundType
     *
     * @param WelfareFund $fundType
     *
     * @return $this
     */
    public function setFundType(WelfareFund $fundType)
    {
        $this->fundType = $fundType;

        return $this;
    }

    /**
     * Get fundType
     *
     * @return WelfareFund
     */
    public function getFundType()
    {
        return $this->fundType;
    }

    /**
     * @return ExServiceman
     */
    public function getServiceman()
    {
        return $this->serviceman;
    }

    /**
     * @param ExServiceman $serviceman
     *
     * @return FundReceived
     */
    public function setServiceman($serviceman)
    {
        $this->serviceman = $serviceman;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSystemGenerated()
    {
        return $this->systemGenerated;
    }

    /**
     * @param bool $systemGenerated
     */
    public function setSystemGenerated($systemGenerated)
    {
        $this->systemGenerated = $systemGenerated;
    }

    public function isReadonly()
    {
        return $this->systemGenerated;
    }

    public function setReadonly($flag)
    {
        //nothing to do
    }
}

