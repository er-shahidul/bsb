<?php

namespace WelfareBundle\Workflow;

use Devnet\WorkflowBundle\Core\WorkflowDefinition;
use WelfareBundle\Entity\MicroCreditApplication;

class MicroCreditApplicationWorkflow extends WorkflowDefinition
{
    public static function getSupports()
    {
        return [MicroCreditApplication::class];
    }

    public static function getName()
    {
        return 'welfare_micro_credit_application';
    }

    public static function getTransitionsConfig()
    {
        return [
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
            'send_back_to_dasb_clerk' => [
                'from' => 'wait_for_io_rejection',
                'to' => 'draft',
                'label' => 'Send Back to DASB Clerk',
                'role' => 'IO',
                'class' => 'red'
            ],
            'reject_by_secretary' => [
                'from' => 'wait_for_secretary',
                'to' => 'rejected',
                'label' => 'Reject',
                'role' => 'SECRETARY',
                'class' => 'dark'
            ],
            'send_back_to_io' => [
                'from' => 'wait_for_secretary',
                'to' => 'wait_for_io_rejection',
                'label' => 'Send Back to IO',
                'role' => 'SECRETARY',
                'class' => 'red'
            ],
            'send_to_basb' => [
                'from' => 'wait_for_secretary',
                'to' => 'wait_for_clerk',
                'label' => 'Forward to BASB',
                'role' => 'SECRETARY'
            ],
            'forward_to_head_clerk' => [
                'from' => 'wait_for_clerk',
                'to' => 'wait_for_head_clerk',
                'label' => 'Forward to Head Clerk',
                'role' => 'WELFARE_CLERK'
            ],
            'forward_to_ao' => [
                'from' => 'wait_for_head_clerk',
                'to' => 'wait_for_ao',
                'label' => 'Forward to AO',
                'role' => 'HEAD_CLERK'
            ],
            'forward_to_dd' => [
                'from' => 'wait_for_ao',
                'to' => 'wait_for_dd',
                'label' => 'Forward to DD',
                'role' => 'AO'
            ],
            'forward_to_director' => [
                'from' => 'wait_for_dd',
                'to' => 'wait_for_director',
                'label' => 'Forward to Director',
                'role' => 'DD'
            ],
            'reject_by_director' => [
                'from' => 'wait_for_director',
                'to' => 'rejected',
                'label' => 'Reject',
                'role' => 'DIRECTOR',
                'class' => 'dark'
            ],
            'approve_by_director' => [
                'from' => 'wait_for_director',
                'to' => 'nominated',
                'label' => 'Approve ',
                'role' => 'DIRECTOR'
            ],
            'board_member_application_selection' => [
                'from' => 'post_nominated',
                'to' => 'wait_for_board_member_grant',
                'label' => '',
                'role' => ''
            ],
            'board_member_recommend_application' => [
                'from' => 'post_board_member_grant',
                'to' => 'recommended',
                'label' => '',
                'role' => ''
            ],
            'grant_reject_by_director' => [
                'from' => 'wait_for_director_grant',
                'to' => 'rejected',
                'label' => 'Reject',
                'role' => 'DIRECTOR'
            ],
            'grant_approve_by_director' => [
                'from' => 'wait_for_director_grant',
                'to' => 'completed',
                'label' => 'Approve',
                'role' => 'DIRECTOR'
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