<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExFamilyInformation
 * @ORM\Table(name="ex_family_information")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\ExFamilyInformationRepository")
 */
class ExFamilyInformation extends BaseFamilyInformation
{
    /**
     * @var ExServiceman
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ExServiceman", inversedBy="families", cascade={"persist"})
     */
    protected $serviceman;
}