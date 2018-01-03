<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MilitaryPersonnel
 */
trait MilitaryPersonnel
{
    /**
     * @var string
     *
     * @ORM\Column(name="regimental_number", type="string", length=255, nullable=true)
     */
    private $regimentalNumber;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\Service")
     */
    private $service;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\Rank")
     */
    private $rank;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\Corp")
     */
    private $corp;

    /**
     * Set regimentalNumber
     *
     * @param string $regimentalNumber
     *
     * @return $this
     */
    public function setRegimentalNumber($regimentalNumber)
    {
        $this->regimentalNumber = $regimentalNumber;

        return $this;
    }

    /**
     * Get regimentalNumber
     *
     * @return string
     */
    public function getRegimentalNumber()
    {
        return $this->regimentalNumber;
    }

    /**
     * Set service
     *
     * @param Service $service
     *
     * @return $this
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return mixed
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @param mixed $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * @return mixed
     */
    public function getCorp()
    {
        return $this->corp;
    }

    /**
     * @param mixed $corp
     */
    public function setCorp($corp)
    {
        $this->corp = $corp;
    }
}

