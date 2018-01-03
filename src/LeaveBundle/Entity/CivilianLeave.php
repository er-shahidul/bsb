<?php

namespace LeaveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CivilianLeave
 *
 * @ORM\Entity(repositoryClass="LeaveBundle\Repository\LeaveRepository")
 */
class CivilianLeave extends Leave
{
    public function getType()
    {
        return 'civilian';
    }
}

