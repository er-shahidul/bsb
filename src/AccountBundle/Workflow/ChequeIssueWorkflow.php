<?php

namespace AccountBundle\Workflow;

use AccountBundle\Entity\ChequeIssue;

class ChequeIssueWorkflow extends AccountBaseWorkflow
{
    public static function getSupports()
    {
        return [ChequeIssue::class];
    }

    public static function getName()
    {
        return 'cheque_issue';
    }
}