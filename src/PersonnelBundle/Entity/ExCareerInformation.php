<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExCareerInformation
 *
 * @ORM\Table(name="ex_career_information")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\ExCareerInformationRepository")
 */
class ExCareerInformation extends BaseCareerInformation
{
    /**
     * @var ExServiceman
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ExServiceman", inversedBy="careers", cascade={"persist"})
     */
    protected $serviceman;
}

