<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\SanctionReceiveRepository")
 */
class SanctionReceive extends SanctionEntry{

    public function getType()
    {
        return 'receive';
    }
}