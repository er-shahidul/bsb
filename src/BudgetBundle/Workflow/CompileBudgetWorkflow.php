<?php

namespace BudgetBundle\Workflow;


use BudgetBundle\Entity\BudgetSummary;
use Devnet\WorkflowBundle\Core\WorkflowDefinition;

class CompileBudgetWorkflow extends WorkflowDefinition
{
    public static function getSupports()
    {
        return [BudgetSummary::class];
    }

    public static function getName()
    {
        return 'budget_compilation';
    }

    public static function getTransitionsConfig()
    {
        return [
            'forward_to_head_clerk' => [
                'from' => 'draft',
                'to' => 'compilation_wait_for_head_clerk',
                'label' => 'Forward to Head Clerk',
                'role' => 'BUDGET_CLERK'
            ],
            'send_back_to_clerk' => [
                'from' => 'compilation_wait_for_hc_rejection',
                'to' => 'draft',
                'label' => 'Send Back to Clerk',
                'role' => 'HEAD_CLERK',
                'class' => 'red'
            ],
            'forward_to_ao' => [
                'from' => 'compilation_wait_for_head_clerk',
                'to' => 'compilation_wait_for_ao',
                'label' => 'Forward to AO',
                'role' => 'HEAD_CLERK'
            ],
            'send_back_to_head_clerk' => [
                'from' => 'compilation_wait_for_ao',
                'to' => 'compilation_wait_for_hc_rejection',
                'label' => 'Send Back to Head Clerk',
                'role' => 'AO',
                'class' => 'red'
            ],
            'forward_to_dd' => [
                'from' => 'compilation_wait_for_ao',
                'to' => 'compilation_wait_for_dd',
                'label' => 'Forward to DD',
                'role' => 'AO'
            ],
            'send_back_to_ao' => [
                'from' => 'compilation_wait_for_dd',
                'to' => 'compilation_wait_for_ao',
                'label' => 'Send Back to AO',
                'role' => 'DD',
                'class' => 'red'
            ],
            'forward_to_director' => [
                'from' => 'compilation_wait_for_dd',
                'to' => 'compilation_wait_for_director',
                'label' => 'Forward to Director',
                'role' => 'DD'
            ],
            'send_back_to_dd' => [
                'from' => 'compilation_wait_for_director',
                'to' => 'compilation_wait_for_dd',
                'label' => 'Send Back to DD',
                'role' => 'DIRECTOR',
                'class' => 'red'
            ],
            'acknowledge_by_director' => [
                'from' => 'compilation_wait_for_director',
                'to' => 'allocation_wait_for_clerk',
                'label' => 'Approve',
                'role' => 'DIRECTOR'
            ],
            'alloc_forward_to_head_clerk' => [
                'from' => 'allocation_wait_for_clerk',
                'to' => 'allocation_wait_for_head_clerk',
                'label' => 'Forward to Head Clerk',
                'role' => 'BUDGET_CLERK'
            ],
            'alloc_send_back_to_clerk' => [
                'from' => 'allocation_wait_for_hc_rejection',
                'to' => 'allocation_wait_for_clerk',
                'label' => 'Send Back to Clerk',
                'role' => 'HEAD_CLERK',
                'class' => 'red'
            ],
            'alloc_forward_to_ao' => [
                'from' => 'allocation_wait_for_head_clerk',
                'to' => 'allocation_wait_for_ao',
                'label' => 'Forward to AO',
                'role' => 'HEAD_CLERK'
            ],
            'alloc_send_back_to_head_clerk' => [
                'from' => 'allocation_wait_for_ao',
                'to' => 'allocation_wait_for_hc_rejection',
                'label' => 'Send Back to Head Clerk',
                'role' => 'AO',
                'class' => 'red'
            ],
            'alloc_forward_to_dd' => [
                'from' => 'allocation_wait_for_ao',
                'to' => 'allocation_wait_for_dd',
                'label' => 'Forward to DD',
                'role' => 'AO'
            ],
            'alloc_send_back_to_ao' => [
                'from' => 'allocation_wait_for_dd',
                'to' => 'allocation_wait_for_ao',
                'label' => 'Send Back to AO',
                'role' => 'DD',
                'class' => 'red'
            ],
            'alloc_forward_to_director' => [
                'from' => 'allocation_wait_for_dd',
                'to' => 'allocation_wait_for_director',
                'label' => 'Forward to Director',
                'role' => 'DD'
            ],
            'alloc_send_back_to_dd' => [
                'from' => 'allocation_wait_for_director',
                'to' => 'allocation_wait_for_dd',
                'label' => 'Send Back to DD',
                'role' => 'DIRECTOR',
                'class' => 'red'
            ],
            'alloc_acknowledge_by_director' => [
                'from' => 'allocation_wait_for_director',
                'to' => 'distribution_wait_for_clerk',
                'label' => 'approve',
                'role' => 'DIRECTOR'
            ],
            'distribution_forward_to_head_clerk' => [
                'from' => 'distribution_wait_for_clerk',
                'to' => 'distribution_wait_for_head_clerk',
                'label' => 'Forward to Head Clerk',
                'role' => 'BUDGET_CLERK'
            ],
            'distribution_send_back_to_clerk' => [
                'from' => 'distribution_wait_for_hc_rejection',
                'to' => 'distribution_wait_for_clerk',
                'label' => 'Send Back to Clerk',
                'role' => 'HEAD_CLERK',
                'class' => 'red'
            ],
            'distribution_forward_to_ao' => [
                'from' => 'distribution_wait_for_head_clerk',
                'to' => 'distribution_wait_for_ao',
                'label' => 'Forward to AO',
                'role' => 'HEAD_CLERK'
            ],
            'distribution_send_back_to_head_clerk' => [
                'from' => 'distribution_wait_for_ao',
                'to' => 'distribution_wait_for_hc_rejection',
                'label' => 'Send Back to Head Clerk',
                'role' => 'AO',
                'class' => 'red'
            ],
            'distribution_forward_to_dd' => [
                'from' => 'distribution_wait_for_ao',
                'to' => 'distribution_wait_for_dd',
                'label' => 'Forward to DD',
                'role' => 'AO'
            ],
            'distribution_send_back_to_ao' => [
                'from' => 'distribution_wait_for_dd',
                'to' => 'distribution_wait_for_ao',
                'label' => 'Send Back to AO',
                'role' => 'DD',
                'class' => 'red'
            ],
            'distribution_forward_to_director' => [
                'from' => 'distribution_wait_for_dd',
                'to' => 'distribution_wait_for_director',
                'label' => 'Forward to Director',
                'role' => 'DD'
            ],
            'distribution_send_back_to_dd' => [
                'from' => 'distribution_wait_for_director',
                'to' => 'distribution_wait_for_dd',
                'label' => 'Send Back to DD',
                'role' => 'DIRECTOR',
                'class' => 'red'
            ],
            'distribution_approved_by_director' => [
                'from' => 'distribution_wait_for_director',
                'to' => 'completed',
                'label' => 'approve',
                'role' => 'DIRECTOR'
            ],

            'amendmentrequest_forward_to_head_clerk' => [
                'from' => 'amendmentrequest_wait_for_clerk',
                'to' => 'amendmentrequest_wait_for_head_clerk',
                'label' => 'Forward to Head Clerk',
                'role' => 'BUDGET_CLERK'
            ],
            'amendmentrequest_send_back_to_clerk' => [
                'from' => 'amendmentrequest_wait_for_hc_rejection',
                'to' => 'amendmentrequest_wait_for_clerk',
                'label' => 'Send Back to Clerk',
                'role' => 'HEAD_CLERK',
                'class' => 'red'
            ],
            'amendmentrequest_forward_to_ao' => [
                'from' => 'amendmentrequest_wait_for_head_clerk',
                'to' => 'amendmentrequest_wait_for_ao',
                'label' => 'Forward to AO',
                'role' => 'HEAD_CLERK'
            ],
            'amendmentrequest_send_back_to_head_clerk' => [
                'from' => 'amendmentrequest_wait_for_ao',
                'to' => 'amendmentrequest_wait_for_hc_rejection',
                'label' => 'Send Back to Head Clerk',
                'role' => 'AO',
                'class' => 'red'
            ],
            'amendmentrequest_forward_to_dd' => [
                'from' => 'amendmentrequest_wait_for_ao',
                'to' => 'amendmentrequest_wait_for_dd',
                'label' => 'Forward to DD',
                'role' => 'AO'
            ],
            'amendmentrequest_send_back_to_ao' => [
                'from' => 'amendmentrequest_wait_for_dd',
                'to' => 'amendmentrequest_wait_for_ao',
                'label' => 'Send Back to AO',
                'role' => 'DD',
                'class' => 'red'
            ],
            'amendmentrequest_forward_to_director' => [
                'from' => 'amendmentrequest_wait_for_dd',
                'to' => 'amendmentrequest_wait_for_director',
                'label' => 'Forward to Director',
                'role' => 'DD'
            ],
            'amendmentrequest_send_back_to_dd' => [
                'from' => 'amendmentrequest_wait_for_director',
                'to' => 'amendmentrequest_wait_for_dd',
                'label' => 'Send Back to DD',
                'role' => 'DIRECTOR',
                'class' => 'red'
            ],
            'amendmentrequest_approved_by_director' => [
                'from' => 'amendmentrequest_wait_for_director',
                'to' => 'amendmentsanction_wait_for_clerk',
                'label' => 'approve',
                'role' => 'DIRECTOR'
            ],

            'amendmentsanction_forward_to_head_clerk' => [
                'from' => 'amendmentsanction_wait_for_clerk',
                'to' => 'amendmentsanction_wait_for_head_clerk',
                'label' => 'Forward to Head Clerk',
                'role' => 'BUDGET_CLERK'
            ],
            'amendmentsanction_send_back_to_clerk' => [
                'from' => 'amendmentsanction_wait_for_hc_rejection',
                'to' => 'amendmentsanction_wait_for_clerk',
                'label' => 'Send Back to Clerk',
                'role' => 'HEAD_CLERK',
                'class' => 'red'
            ],
            'amendmentsanction_forward_to_ao' => [
                'from' => 'amendmentsanction_wait_for_head_clerk',
                'to' => 'amendmentsanction_wait_for_ao',
                'label' => 'Forward to AO',
                'role' => 'HEAD_CLERK'
            ],
            'amendmentsanction_send_back_to_head_clerk' => [
                'from' => 'amendmentsanction_wait_for_ao',
                'to' => 'amendmentsanction_wait_for_hc_rejection',
                'label' => 'Send Back to Head Clerk',
                'role' => 'AO',
                'class' => 'red'
            ],
            'amendmentsanction_forward_to_dd' => [
                'from' => 'amendmentsanction_wait_for_ao',
                'to' => 'amendmentsanction_wait_for_dd',
                'label' => 'Forward to DD',
                'role' => 'AO'
            ],
            'amendmentsanction_send_back_to_ao' => [
                'from' => 'amendmentsanction_wait_for_dd',
                'to' => 'amendmentsanction_wait_for_ao',
                'label' => 'Send Back to AO',
                'role' => 'DD',
                'class' => 'red'
            ],
            'amendmentsanction_forward_to_director' => [
                'from' => 'amendmentsanction_wait_for_dd',
                'to' => 'amendmentsanction_wait_for_director',
                'label' => 'Forward to Director',
                'role' => 'DD'
            ],
            'amendmentsanction_send_back_to_dd' => [
                'from' => 'amendmentsanction_wait_for_director',
                'to' => 'amendmentsanction_wait_for_dd',
                'label' => 'Send Back to DD',
                'role' => 'DIRECTOR',
                'class' => 'red'
            ],
            'amendmentsanction_approved_by_director' => [
                'from' => 'amendmentsanction_wait_for_director',
                'to' => 'completed',
                'label' => 'Approve',
                'role' => 'DIRECTOR'
            ]
        ];
    }

    public static function getEditablePlaces()
    {
        return [
            'draft',
            'allocation_wait_for_clerk',
            'distribution_wait_for_clerk',
            'amendmentrequest_wait_for_clerk',
            'amendmentsanction_wait_for_clerk'
        ];
    }
}