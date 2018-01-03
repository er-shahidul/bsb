<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServingMilitary
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\ServingMilitaryRepository")
 */
class ServingMilitary extends ServingPersonnel
{
    /**
     * @ORM\OneToOne(targetEntity="PersonnelBundle\Entity\MilitaryProfile", mappedBy="servingMilitary", cascade={"persist"})
     */
    private $militaryProfile;

    /**
     * @return mixed
     */
    public function getMilitaryProfile()
    {
        return $this->militaryProfile;
    }

    /**
     * @param MilitaryProfile $militaryProfile
     *
     * @return ServingMilitary
     */
    public function setMilitaryProfile($militaryProfile)
    {
        $militaryProfile->setServingMilitary($this);
        $this->militaryProfile = $militaryProfile;

        return $this;
    }

    /**
     * Set soldierNumber
     *
     * @param string $soldierNumber
     *
     * @return $this
     */
    public function setSoldierNumber($soldierNumber)
    {
        $this->setIdentityNumber($soldierNumber);

        return $this;
    }

    /**
     * Get soldierNumber
     *
     * @return string
     */
    public function getSoldierNumber()
    {
        return $this->getIdentityNumber();
    }
}

