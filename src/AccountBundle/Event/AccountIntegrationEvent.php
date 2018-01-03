<?php

namespace AccountBundle\Event;

use AccountBundle\Mapper\Sanction;
use AccountBundle\Mapper\Voucher;
use AccountBundle\Mapper\VoucherDetail;
use Symfony\Component\EventDispatcher\Event;

class AccountIntegrationEvent extends Event
{
    const ACCOUNT_MAKE_PAYMENT_EVENT = 'account.make_payment';
    protected $entity;
    protected $office;

    public function __construct($entity, $office)
    {
        $this->entity = $entity;
        $this->office = $office;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getOffice()
    {
        return $this->office;
    }

    static function sanctionFactory($label, $description = null)
    {
        return (new Sanction($label))->setDescription($description);
    }

    static function voucherFactory($amount, $paymentTo = null, $paymentAgainst = null)
    {
        return (new Voucher())->setAmount($amount)->setPaymentTo($paymentTo)->setPaymentAgainst($paymentAgainst);
    }

    static function voucherDetailFactory($amount, $type = null)
    {
        return (new VoucherDetail())->setAmount($amount)->setFundHead($type);
    }
}
