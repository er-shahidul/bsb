<?php

namespace BudgetBundle\Workflow;


use BudgetBundle\Entity\Budget;
use Devnet\WorkflowBundle\Core\WorkflowDefinition;

class OfficeBudgetWorkflow extends WorkflowDefinition
{
    public static function getSupports()
    {
        return [Budget::class];
    }

    public static function getName()
    {
        return 'office_budget';
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
            'send_back_to_io' => [
                'from' => 'wait_for_secretary',
                'to' => 'wait_for_io_rejection',
                'label' => 'Send Back to IO',
                'role' => 'SECRETARY',
                'class' => 'red'
            ],
            'send_to_basb' => [
                'from' => 'wait_for_secretary',
                'to' => 'wait_for_basb_clerk',
                'label' => 'Forward to BASB',
                'role' => 'SECRETARY'
            ],
            'send_back_to_dasb_clerk' => [
                'from' => 'wait_for_io_rejection',
                'to' => 'draft',
                'label' => 'Send Back to DASB Clerk',
                'role' => 'IO',
                'class' => 'red'
            ],
            'received_by_clerk' => [
                'from' => 'wait_for_basb_clerk',
                'to' => 'wait_for_budget_compilation',
                'label' => 'Receive',
                'role' => 'BUDGET_CLERK'
            ],
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
                'role' => 'AO',
                'label' => 'Send Back to Head Clerk',
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
                'role' => 'DD',
                'label' => 'Send Back to AO',
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
                'role' => 'DIRECTOR',
                'label' => 'Send Back to DD',
                'class' => 'red'
            ],
            'acknowledge_by_director' => [
                'from' => 'wait_for_director',
                'to' => 'wait_for_budget_compilation',
                'label' => 'Approve',
                'role' => 'DIRECTOR'
            ],
            'approve_by_secretary' => [
                'from' => 'wait_for_secretary_approval',
                'to' => 'approved',
                'label' => 'Acknowledge',
                'role' => 'SECRETARY'
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