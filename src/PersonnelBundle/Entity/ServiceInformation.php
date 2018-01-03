<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExServiceInformation
 *
 * @ORM\Table(name="service_information")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\ExServiceInformationRepository")
 */
class ServiceInformation extends BaseServiceInformation
{
    /**
     * @var ServingPersonnel
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ServingPersonnel", inversedBy="servicesInfo", cascade={"persist"})
     */
    protected $serviceman;
}

