<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payee
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\PayeePayerRepository")
 */
class Payee extends PayeePayer
{
    public function getType()
    {
        return 'payee';
    }
}