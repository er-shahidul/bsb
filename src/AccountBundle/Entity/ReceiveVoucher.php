<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\ReceiveVoucherRepository")
 */
class ReceiveVoucher extends Voucher {

    public function getReceivedFrom()
    {
        return $this->getToOrFrom();
    }

    public function setReceivedFrom($value)
    {
        $this->setToOrFrom($value);
    }

    public function voucherType()
    {
        return 'receive';
    }
}

