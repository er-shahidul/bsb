<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\SanctionMiscellaneousRepository")
 */
class SanctionMiscellaneous extends SanctionEntry{

    public function getType()
    {
        return 'miscellaneous';
    }
}