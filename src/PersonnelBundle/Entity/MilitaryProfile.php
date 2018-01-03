<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MilitaryProfile
 * @ORM\Table(name="military_profiles")
 * @ORM\Entity()
 */
class MilitaryProfile
{
    use MilitaryPersonnel;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="PersonnelBundle\Entity\ServingMilitary", inversedBy="militaryProfile", cascade={"persist"})
     */
    private $servingMilitary;

    /**
     * @return mixed
     */
    public function getServingMilitary()
    {
        return $this->servingMilitary;
    }

    /**
     * @param mixed $servingMilitary
     *
     * @return MilitaryProfile
     */
    public function setServingMilitary($servingMilitary)
    {
        $this->servingMilitary = $servingMilitary;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}