<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\PaymentVoucherRepository")
 */
class PaymentVoucher extends Voucher{

    public function getPaymentTo()
    {
        return $this->getToOrFrom();
    }

    public function setPaymentTo($value)
    {
        $this->setToOrFrom($value);
    }

    public function voucherType()
    {
        return 'payment';
    }
}

