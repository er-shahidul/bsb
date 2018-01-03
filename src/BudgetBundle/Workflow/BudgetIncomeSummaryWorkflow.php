<?php

namespace BudgetBundle\Workflow;


use BudgetBundle\Entity\BudgetIncomeSummary;
use Devnet\WorkflowBundle\Core\WorkflowDefinition;

class BudgetIncomeSummaryWorkflow extends WorkflowDefinition
{
    public static function getSupports()
    {
        return [BudgetIncomeSummary::class];
    }

    public static function getName()
    {
        return 'budget_income_summary';
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
            'approved_by_director' => [
                'from' => 'wait_for_director',
                'to' => 'approved',
                'label' => 'Approve',
                'role' => 'DIRECTOR'
            ],
            'amendment_forward_to_head_clerk' => [
                'from' => 'amendment_wait_for_clerk',
                'to' => 'amendment_wait_for_head_clerk',
                'label' => 'Forward to Head Clerk',
                'role' => 'BUDGET_CLERK'
            ],
            'amendment_send_back_to_clerk' => [
                'from' => 'amendment_wait_for_hc_rejection',
                'to' => 'amendment_wait_for_clerk',
                'label' => 'Send Back to Clerk',
                'role' => 'HEAD_CLERK',
                'class' => 'red'
            ],
            'amendment_forward_to_ao' => [
                'from' => 'amendment_wait_for_head_clerk',
                'to' => 'amendment_wait_for_ao',
                'label' => 'Forward to AO',
                'role' => 'HEAD_CLERK'
            ],
            'amendment_send_back_to_head_clerk' => [
                'from' => 'amendment_wait_for_ao',
                'to' => 'amendment_wait_for_hc_rejection',
                'label' => 'Send Back to Head Clerk',
                'role' => 'AO',
                'class' => 'red'
            ],
            'amendment_forward_to_dd' => [
                'from' => 'amendment_wait_for_ao',
                'to' => 'amendment_wait_for_dd',
                'label' => 'Forward to DD',
                'role' => 'AO'
            ],
            'amendment_send_back_to_ao' => [
                'from' => 'amendment_wait_for_dd',
                'to' => 'amendment_wait_for_ao',
                'label' => 'Send Back to AO',
                'role' => 'DD',
                'class' => 'red'
            ],
            'amendment_forward_to_director' => [
                'from' => 'amendment_wait_for_dd',
                'to' => 'amendment_wait_for_director',
                'label' => 'Forward to Director',
                'role' => 'DD'
            ],
            'amendment_send_back_to_dd' => [
                'from' => 'amendment_wait_for_director',
                'to' => 'amendment_wait_for_dd',
                'label' => 'Send Back to DD',
                'role' => 'DIRECTOR',
                'class' => 'red'
            ],
            'amendment_approved_by_director' => [
                'from' => 'amendment_wait_for_director',
                'to' => 'approved',
                'label' => 'Approve',
                'role' => 'DIRECTOR'
            ]
        ];
    }

    public static function getEditablePlaces()
    {
        return [
          'draft',
          'amendment_wait_for_clerk'
        ];
    }
}