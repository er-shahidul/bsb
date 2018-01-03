<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServingPersonnelEmploymentInformation
 * @ORM\Table(name="employments_information")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\EmploymentInformationRepository")
 */
class EmploymentInformation extends BaseEmploymentInformation
{
    /**
     * @var ServingPersonnel
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ServingPersonnel", inversedBy="employmentInformations", cascade={"persist"})
     */
    protected $serviceman;
}