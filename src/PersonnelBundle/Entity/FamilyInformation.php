<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="family_information")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\FamilyInformationRepository")
 */
class FamilyInformation extends BaseFamilyInformation
{
    /**
     * @var ServingPersonnel
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ServingPersonnel", inversedBy="families", cascade={"persist"})
     */
    protected $serviceman;
}