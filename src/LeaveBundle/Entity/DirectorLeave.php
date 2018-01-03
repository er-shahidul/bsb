<?php

namespace LeaveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DirectorLeave
 *
 * @ORM\Entity(repositoryClass="LeaveBundle\Repository\LeaveRepository")
 */
class DirectorLeave extends Leave
{
    public function getType()
    {
        return 'director';
    }
}

