<?php

namespace AccountBundle\Workflow;

use AccountBundle\Entity\SanctionPayment;

class SanctionPaymentWorkflow extends AccountBaseWorkflow
{
    public static function getSupports()
    {
        return [SanctionPayment::class];
    }

    public static function getName()
    {
        return 'sanction_payment_workflow';
    }
}