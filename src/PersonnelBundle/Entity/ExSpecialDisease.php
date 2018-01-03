<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExSpecialDisease
 * @ORM\Table(name="ex_special_disease")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\ExSpecialDiseaseRepository")
 */
class ExSpecialDisease extends BaseSpecialDisease
{
    /**
     * @var ExServiceman
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ExServiceman", inversedBy="specialDiseases", cascade={"persist"})
     */
    protected $serviceman;
}