<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExEducationalInformation
 *
 * @ORM\Table(name="ex_educational_information")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\ExEducationalInformationRepository")
 */
class ExEducationalInformation extends BaseEducationalInformation
{
    /**
     * @var ExServiceman
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ExServiceman", inversedBy="educations", cascade={"persist"})
     */
    protected $serviceman;
}

