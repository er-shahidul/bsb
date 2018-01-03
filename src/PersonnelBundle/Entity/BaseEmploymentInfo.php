<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MilitaryPersonnel
 */
trait BaseEmploymentInfo
{
    /**
     * @var string
     *
     * @ORM\Column(name="last_served_unit", type="string", length=255)
     */
    private $lastServedUnit;

    /**
     * @var string
     *
     * @ORM\Column(name="identity_number", type="string", length=255, unique=true, nullable=false)
     */
    private $identityNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="joining_date", type="date", nullable=true)
     */
    private $joiningDate;

    /**
     * @var string
     *
     * @ORM\Column(name="joining_place", type="string", length=255, nullable=true)
     */
    private $joiningPlace;

    /**
     * @return string
     */
    public function getIdentityNumber()
    {
        return $this->identityNumber;
    }

    /**
     * @param string $identityNumber
     */
    public function setIdentityNumber($identityNumber)
    {
        $this->identityNumber = $identityNumber;
    }

    /**
     * Set joiningDate
     *
     * @param \DateTime $joiningDate
     *
     * @return $this
     */
    public function setJoiningDate($joiningDate)
    {
        $this->joiningDate = $joiningDate;

        return $this;
    }

    /**
     * Get joiningDate
     *
     * @return \DateTime
     */
    public function getJoiningDate()
    {
        return $this->joiningDate;
    }

    /**
     * @return string
     */
    public function getJoiningPlace()
    {
        return $this->joiningPlace;
    }

    /**
     * @param string $joiningPlace
     */
    public function setJoiningPlace($joiningPlace)
    {
        $this->joiningPlace = $joiningPlace;
    }

    /**
     * @return string
     */
    public function getLastServedUnit()
    {
        return $this->lastServedUnit;
    }

    /**
     * @param string $lastServedUnit
     */
    public function setLastServedUnit($lastServedUnit)
    {
        $this->lastServedUnit = $lastServedUnit;
    }
}

