<?php

namespace LeaveBundle\Workflow;

use LeaveBundle\Entity\GeneralLeave;
use Devnet\WorkflowBundle\Core\WorkflowDefinition;

class GeneralLeaveWorkflow extends WorkflowDefinition
{

    public static function getSupports()
    {
        return [GeneralLeave::class];
    }

    public static function getName()
    {
        return 'general_leave';
    }

    public static function getTransitionsConfig()
    {
        return [
            // FOR DASB
            'forward_to_io' => [
                'from' => 'draft',
                'to' => 'wait_for_io',
                'label' => 'Forward to IO',
                'role' => 'DASB_CLERK'
            ],
            'forward_to_secretary' => [
                'from' => 'wait_for_io',
                'to' => 'wait_for_secretary',
                'label' => 'Forward to Secretary',
                'role' => 'IO'
            ],
            'send_back_to_io' => [
                'from' => 'wait_for_secretary',
                'to' => 'wait_for_io_rejection',
                'label' => 'Send Back to IO',
                'role' => 'SECRETARY',
                'class' => 'red'
            ],
            'send_back_to_dasb_clerk' => [
                'from' => 'wait_for_io_rejection',
                'to' => 'draft',
                'label' => 'Send Back to DASB Clerk',
                'role' => 'IO',
                'class' => 'red'
            ],
            'reject_by_secretary' => [
                'from' => 'wait_for_secretary',
                'to' => 'reject',
                'label' => 'Reject',
                'role' => 'SECRETARY'
            ],
            'approve_by_secretary' => [
                'from' => 'wait_for_secretary',
                'to' => 'approved',
                'label' => 'Approve',
                'role' => 'SECRETARY'
            ],
        ];
    }

    public static function getEditablePlaces()
    {
        return ['draft'];
    }
}