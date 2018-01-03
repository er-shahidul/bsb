<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExTrainingInformation
 *
 * @ORM\Table(name="training_information")
 * @ORM\Entity
 */
class TrainingInformation extends BaseTrainingInformation
{
    /**
     * @var ServingPersonnel
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ServingPersonnel", inversedBy="trainings", cascade={"persist"})
     */
    protected $serviceman;
}

