<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ranks")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\RankRepository")
 */
class Rank extends BaseServiceChildEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\Service", inversedBy="ranks")
     */
    protected $service;

    /**
     * @var string
     * @ORM\Column(name="short_name", type="string", length=255, nullable=true)
     */
    protected $short;

    /**
     * @return string
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * @param string $short
     */
    public function setShort($short)
    {
        $this->short = $short;
    }
}
