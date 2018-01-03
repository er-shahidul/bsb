<?php

namespace AccountBundle\Workflow;

use AppBundle\Workflow\GenericWorkflow;

abstract class AccountBaseWorkflow extends GenericWorkflow
{
    public static function getTransitionsConfig()
    {
        $transitions = parent::getTransitionsConfig();

        unset($transitions['reject_by_director']);
        unset($transitions['reject_by_secretary']);
        $transitions['forward_to_head_clerk']['role'] = 'ACCOUNT_CLERK';

        return $transitions;
    }
}