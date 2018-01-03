<?php

namespace BudgetBundle\Workflow;


use BudgetBundle\Entity\BudgetExpenseSanction;
use BudgetBundle\Entity\BudgetIncome;
use Devnet\WorkflowBundle\Core\WorkflowDefinition;

class ExpenseSanctionWorkflow extends WorkflowDefinition
{
    public static function getSupports()
    {
        return [
            BudgetExpenseSanction::class
        ];
    }

    public static function getName()
    {
        return 'expense_sanction';
    }

    public static function getTransitionsConfig()
    {
        return [
            'init_draft' => [
                'from' => 'create',
                'to' => 'draft',
                'label' => '',
                'role' => 'DIRECTOR'
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