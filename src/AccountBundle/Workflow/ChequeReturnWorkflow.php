<?php

namespace AccountBundle\Workflow;

use AccountBundle\Entity\ChequeReturn;

class ChequeReturnWorkflow extends AccountBaseWorkflow
{
    public static function getTransitionsConfig()
    {
        $transitions = [
            'init_draft' => [
                'from' => 'create',
                'to' => 'draft',
                'label' => '',
                'role' => 'DIRECTOR'
            ]
        ];
        $transitions = array_merge_recursive($transitions, parent::getTransitionsConfig());
        unset($transitions['reject_by_director']);
        unset($transitions['reject_by_secretary']);
        $transitions['forward_to_head_clerk']['role'] = 'ACCOUNT_CLERK';

        return $transitions;
    }

    public static function getSupports()
    {
        return [ChequeReturn::class];
    }

    public static function getName()
    {
        return 'cheque_return_workflow';
    }
}