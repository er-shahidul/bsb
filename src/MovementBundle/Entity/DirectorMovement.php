<?php

namespace MovementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DirectorMovement
 *
 * @ORM\Entity(repositoryClass="MovementBundle\Repository\MovementRepository")
 */
class DirectorMovement extends Movement
{
    public function getType()
    {
        return 'director';
    }
}

