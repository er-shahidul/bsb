<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="corps")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\DistrictRepository")
 */
class Corp extends BaseServiceChildEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\Service", inversedBy="corps")
     */
    protected $service;
}
