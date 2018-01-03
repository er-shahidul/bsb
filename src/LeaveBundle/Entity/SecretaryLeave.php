<?php

namespace LeaveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SecretaryLeave
 *
 * @ORM\Entity(repositoryClass="LeaveBundle\Repository\LeaveRepository")
 */
class SecretaryLeave extends Leave
{
    public function getType()
    {
        return 'secretary';
    }
}

