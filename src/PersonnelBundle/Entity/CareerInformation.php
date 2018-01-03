<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExCareerInformation
 *
 * @ORM\Table(name="career_information")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\ExCareerInformationRepository")
 */
class CareerInformation extends BaseCareerInformation
{
    /**
     * @var ServingPersonnel
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ServingPersonnel", inversedBy="careers", cascade={"persist"})
     */
    protected $serviceman;
}

