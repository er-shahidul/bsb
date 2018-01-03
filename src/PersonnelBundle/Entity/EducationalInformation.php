<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExEducationalInformation
 *
 * @ORM\Table(name="educational_information")
 * @ORM\Entity
 */
class EducationalInformation extends BaseEducationalInformation
{
    /**
     * @var ServingPersonnel
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ServingPersonnel", inversedBy="educations", cascade={"persist"})
     */
    protected $serviceman;
}

