<?php

namespace AccountBundle\Workflow;

use AccountBundle\Entity\PaymentVoucher;

class PaymentVoucherWorkflow extends AccountBaseWorkflow
{
    public static function getSupports()
    {
        return [PaymentVoucher::class];
    }

    public static function getTransitionsConfig()
    {
        $transitions = parent::getTransitionsConfig();
        $transitions['forward_to_head_clerk']['role'] = 'ACCOUNT_CLERK';

        return $transitions;
    }

    public static function getName()
    {
        return 'payment_voucher';
    }
}