<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\SanctionPaymentRepository")
 */
class SanctionPayment extends SanctionEntry{

    public function getType()
    {
        return 'payment';
    }
}