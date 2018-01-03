<?php

namespace AccountBundle\Workflow;

use AccountBundle\Entity\ChequeReconciliation;

class ChequeReconciliationWorkflow extends AccountBaseWorkflow
{
    public static function getSupports()
    {
        return [ChequeReconciliation::class];
    }

    public static function getName()
    {
        return 'cheque_reconciliation_workflow';
    }
}