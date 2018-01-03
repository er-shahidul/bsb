<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payer
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\PayeePayerRepository")
 */
class Payer extends PayeePayer
{
    public function getType()
    {
        return 'payer';
    }
}

