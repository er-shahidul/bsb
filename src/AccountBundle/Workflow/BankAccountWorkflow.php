<?php

namespace AccountBundle\Workflow;

use AccountBundle\Entity\BankAccount;
use AppBundle\Workflow\GenericWorkflow;

class BankAccountWorkflow extends GenericWorkflow
{
    public static function getSupports()
    {
        return [BankAccount::class];
    }

    public static function getTransitionsConfig()
    {
        $transitions = parent::getTransitionsConfig();

        $transitions['forward_to_head_clerk']['role'] = 'ACCOUNT_CLERK';

        return $transitions;
    }

    public static function getName()
    {
        return 'accounts_bank_account';
    }
}