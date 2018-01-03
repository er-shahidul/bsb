<?php

namespace PersonnelBundle\Entity;

use AppBundle\Entity\SortableMasterData;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="services")
 * @ORM\Entity()
 */
class Service extends SortableMasterData
{
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\Corp", mappedBy="service")
     */
    protected $corps;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\Rank", mappedBy="service")
     */
    protected $ranks;

    public function __construct()
    {
        $this->corps = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * @return ArrayCollection
     */
    public function getRanks()
    {
        return $this->ranks;
    }
}
