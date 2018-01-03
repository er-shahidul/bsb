<?php

namespace BudgetBundle\Workflow;


use BudgetBundle\Entity\PreBudgetSummary;
use Devnet\WorkflowBundle\Core\WorkflowDefinition;

class PreBudgetSummaryWorkflow extends WorkflowDefinition
{
    public static function getSupports()
    {
        return [PreBudgetSummary::class];
    }

    public static function getName()
    {
        return 'pre_budget_summary';
    }

    public static function getTransitionsConfig()
    {
        return [
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
            'approve_by_director' => [
                'from' => 'wait_for_director',
                'to' => 'approved',
                'label' => 'Approve',
                'role' => 'DIRECTOR'
            ],
            'approval_forward_to_head_clerk' => [
                'from' => 'approval_wait_for_clerk',
                'to' => 'approval_wait_for_head_clerk',
                'label' => 'Forward to Head Clerk',
                'role' => 'BUDGET_CLERK'
            ],
            'approval_send_back_to_clerk' => [
                'from' => 'approval_wait_for_hc_rejection',
                'to' => 'approval_wait_for_clerk',
                'label' => 'Send Back to Clerk',
                'role' => 'HEAD_CLERK',
                'class' => 'red'
            ],
            'approval_forward_to_ao' => [
                'from' => 'approval_wait_for_head_clerk',
                'to' => 'approval_wait_for_ao',
                'label' => 'Forward to AO',
                'role' => 'HEAD_CLERK'
            ],
            'approval_ao_send_back_to_dasb_secretary' => [
                'from' => 'approval_wait_for_ao',
                'to' => 'wait_for_secretary',
                'label' => 'Send Back to DASB Secretary',
                'role' => 'AO',
                'class' => 'red'
            ],
            'approval_forward_to_dd' => [
                'from' => 'approval_wait_for_ao',
                'to' => 'approval_wait_for_dd',
                'label' => 'Forward to DD',
                'role' => 'AO'
            ],
            'approval_dd_send_back_to_dasb_secretary' => [
                'from' => 'approval_wait_for_dd',
                'to' => 'wait_for_secretary',
                'label' => 'Send Back to DASB Secretary',
                'role' => 'DD',
                'class' => 'red'
            ],
            'approval_forward_to_director' => [
                'from' => 'approval_wait_for_dd',
                'to' => 'approval_wait_for_director',
                'label' => 'Forward to Director',
                'role' => 'DD'
            ],
            'approval_director_send_back_to_dasb_secretary' => [
                'from' => 'approval_wait_for_director',
                'to' => 'wait_for_secretary',
                'label' => 'Send Back to DASB Secretary',
                'role' => 'DIRECTOR',
                'class' => 'red'
            ],
            'approval_approved_by_director' => [
                'from' => 'approval_wait_for_director',
                'to' => 'approved',
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