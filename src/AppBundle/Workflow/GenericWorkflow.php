<?php

namespace AppBundle\Workflow;


use Devnet\WorkflowBundle\Core\WorkflowDefinition;

class GenericWorkflow extends WorkflowDefinition
{
    public static function getSupports()
    {
        return [

        ];
    }

    public static function getName()
    {
        return 'generic_workflow';
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
            // FOR BASB
            'forward_to_head_clerk' => [
                'from' => 'draft',
                'to' => 'wait_for_head_clerk',
                'label' => 'Forward to Head Clerk',
                'role' => 'BUDGET_CLERK'
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
            'forward_to_dd' => [
                'from' => 'wait_for_ao',
                'to' => 'wait_for_dd',
                'label' => 'Forward to DD',
                'role' => 'AO'
            ],
            'send_back_to_ao' => [
                'from' => 'wait_for_dd',
                'to' => 'wait_for_ao',
                'label' => 'Send Back to AO',
                'role' => 'DD',
                'class' => 'red'
            ],
            'forward_to_director' => [
                'from' => 'wait_for_dd',
                'to' => 'wait_for_director',
                'label' => 'Forward to Director',
                'role' => 'DD'
            ],
            'send_back_to_dd' => [
                'from' => 'wait_for_director',
                'to' => 'wait_for_dd',
                'label' => 'Send Back to DD',
                'role' => 'DIRECTOR',
                'class' => 'red'
            ],
            'reject_by_director' => [
                'from' => 'wait_for_director',
                'to' => 'reject',
                'label' => 'Reject',
                'role' => 'DIRECTOR'
            ],
            'approved_by_director' => [
                'from' => 'wait_for_director',
                'to' => 'approved',
                'label' => 'Approve',
                'role' => 'DIRECTOR',
                'class' => 'green'
            ]
        ];
    }

    public static function getEditablePlaces()
    {
        return [
          'draft'
        ];
    }
}