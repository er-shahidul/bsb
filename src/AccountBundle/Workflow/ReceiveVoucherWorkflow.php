<?php

namespace AccountBundle\Workflow;

use AccountBundle\Entity\ReceiveVoucher;
use AppBundle\Workflow\GenericWorkflow;

class ReceiveVoucherWorkflow extends GenericWorkflow
{
    public static function getSupports()
    {
        return [ReceiveVoucher::class];
    }

    public static function getTransitionsConfig()
    {
        $transitions = parent::getTransitionsConfig();
        $transitions['forward_to_head_clerk']['role'] = 'ACCOUNT_CLERK';

        return $transitions;
    }

    public static function getName()
    {
        return 'receive_voucher';
    }

    public static function getEditablePlaces()
    {
        return [
            'draft'
        ];
    }
}