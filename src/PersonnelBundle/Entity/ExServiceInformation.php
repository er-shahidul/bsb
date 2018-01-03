<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExServiceInformation
 *
 * @ORM\Table(name="ex_service_information")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\ExServiceInformationRepository")
 */
class ExServiceInformation extends BaseServiceInformation
{
    /**
     * @var ExServiceman
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ExServiceman", inversedBy="servicesInfo", cascade={"persist"})
     */
    protected $serviceman;
}

