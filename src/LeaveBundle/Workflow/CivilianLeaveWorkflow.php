<?php

namespace LeaveBundle\Workflow;

use LeaveBundle\Entity\CivilianLeave;
use Devnet\WorkflowBundle\Core\WorkflowDefinition;

class CivilianLeaveWorkflow extends WorkflowDefinition
{
    public static function getSupports()
    {
        return [CivilianLeave::class];
    }

    public static function getName()
    {
        return 'civilian_leave';
    }

    public static function getTransitionsConfig()
    {
        return [
            // FOR BASB
            'forward_to_head_clerk' => [
                'from' => 'draft',
                'to' => 'wait_for_head_clerk',
                'label' => 'Forward to Head Clerk',
                'role' => 'ESTABLISHMENT_CLERK'
            ],
            'send_back_to_clerk' => [
                'from' => 'wait_for_hc_rejection',
                'to' => 'draft',
                'label' => 'Send Back to Clerk',
                'role' => 'HEAD_CLERK',
                'class' => 'red'
            ],
            'forward_to_ao' => [
                'from' => 'wait_for_head_clerk',
                'to' => 'wait_for_ao',
                'label' => 'Forward to AO',
                'role' => 'HEAD_CLERK'
            ],
            'send_back_to_head_clerk' => [
                'from' => 'wait_for_ao',
                'to' => 'wait_for_hc_rejection',
                'label' => 'Send Back to Head Clerk',
                'role' => 'AO',
                'class' => 'red'
            ],
            'reject_by_ao' => [
                'from' => 'wait_for_ao',
                'to' => 'reject',
                'label' => 'Reject',
                'role' => 'AO'
            ],
            'approved_by_ao' => [
                'from' => 'wait_for_ao',
                'to' => 'approved',
                'label' => 'Approve',
                'role' => 'AO',
                'class' => 'green'
            ]
        ];

    }

    public static function getEditablePlaces()
    {
        return ['draft'];
    }
}