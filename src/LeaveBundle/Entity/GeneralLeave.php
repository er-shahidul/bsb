<?php

namespace LeaveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GeneralLeave
 *
 * @ORM\Entity(repositoryClass="LeaveBundle\Repository\LeaveRepository")
 */
class GeneralLeave extends Leave
{
    public function getType()
    {
        return 'general';
    }
}

