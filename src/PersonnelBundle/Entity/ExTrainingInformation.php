<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExTrainingInformation
 *
 * @ORM\Table(name="ex_training_information")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\ExTrainingInformationRepository")
 */
class ExTrainingInformation extends BaseTrainingInformation
{
    /**
     * @var ExServiceman
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ExServiceman", inversedBy="trainings", cascade={"persist"})
     */
    protected $serviceman;
}

