<?php

namespace LeaveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MilitaryLeave
 *
 * @ORM\Entity(repositoryClass="LeaveBundle\Repository\LeaveRepository")
 */
class MilitaryLeave extends Leave
{
    public function getType()
    {
        return 'military';
    }
}

