<?php

namespace WelfareBundle\Workflow;

use Devnet\WorkflowBundle\Core\WorkflowDefinition;
use WelfareBundle\Entity\MCDefaulterRegister;
use WelfareBundle\Entity\MicroCreditApplication;
use WelfareBundle\Entity\MicroCreditPayment;

class MCDefaulterRegisterWorkflow extends WorkflowDefinition
{
    public static function getSupports()
    {
        return [MCDefaulterRegister::class];
    }

    public static function getName()
    {
        return 'mc_defaulter_register';
    }

    public static function getTransitionsConfig()
    {
        return [

            'forward_to_head_clerk' => [
                'from' => 'draft',
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
                'to' => 'approved',
                'label' => 'Approve ',
                'role' => 'DIRECTOR'
            ],
        ];
    }

    public static function getEditablePlaces()
    {
        return [
          'draft', 'wait_for_director'
        ];
    }
}