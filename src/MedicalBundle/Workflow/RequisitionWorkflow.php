<?php

namespace MedicalBundle\Workflow;

use MedicalBundle\Entity\Requisition;

class RequisitionWorkflow extends MedicalBaseWorkflow
{
    public static function getSupports()
    {
        return [Requisition::class];
    }

    public static function getName()
    {
        return 'medical_requisition_workflow';
    }

    public static function getEditablePlaces()
    {
        return [
            'draft'
        ];
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
                'to' => 'compilation_wait_for_medicine_clerk',
                'label' => 'Send BASB',
                'role' => 'SECRETARY'
            ],

            'forward_to_head_clerk' => [
                'from' => 'compilation_wait_for_medicine_clerk',
                'to' => 'compilation_wait_for_head_clerk',
                'label' => 'Forward to Head Clerk',
                'role' => 'MEDICINE_CLERK'
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
                'to' => 'distribution_wait_for_clerk',
                'label' => 'Approve',
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
                'to' => 'receive_wait_for_clerk',
                'label' => 'approve',
                'role' => 'DIRECTOR'
            ],

            'receive_forward_to_io' => [
                'from' => 'receive_wait_for_clerk',
                'to' => 'receive_wait_for_io',
                'label' => 'Forward to IO',
                'role' => 'DASB_CLERK'
            ],
            'receive_forward_to_secretary' => [
                'from' => 'receive_wait_for_io',
                'to' => 'receive_wait_for_secretary',
                'label' => 'Forward to Secretary',
                'role' => 'IO'
            ],
            'receive_send_back_to_io' => [
                'from' => 'receive_wait_for_secretary',
                'to' => 'receive_wait_for_io_rejection',
                'label' => 'Send Back to IO',
                'role' => 'SECRETARY',
                'class' => 'red'
            ],
            'receive_send_back_to_dasb_clerk' => [
                'from' => 'receive_wait_for_io_rejection',
                'to' => 'receive_wait_for_clerk',
                'label' => 'Send Back to DASB Clerk',
                'role' => 'IO',
                'class' => 'red'
            ],
            'receive_reject_by_secretary' => [
                'from' => 'wait_for_secretary',
                'to' => 'reject',
                'label' => 'Reject',
                'role' => 'SECRETARY'
            ],
            'receive_approve_by_secretary' => [
                'from' => 'wait_for_secretary',
                'to' => 'approved',
                'label' => 'Approved',
                'role' => 'SECRETARY'
            ],
        ];
    }
}