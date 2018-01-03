<?php

namespace MovementBundle\Workflow;


use Devnet\WorkflowBundle\Core\WorkflowDefinition;
use MovementBundle\Entity\DirectorMovement;

class DirectorMovementWorkflow extends WorkflowDefinition
{

    public static function getSupports()
    {
        return [DirectorMovement::class];
    }

    public static function getName()
    {
        return 'director_movement';
    }

    public static function getTransitionsConfig()
    {
        return [

            // FOR BASB
            'forward_to_director' => [
                'from' => 'draft',
                'to' => 'wait_for_director',
                'label' => 'Forward to Director',
                'role' => 'ESTABLISHMENT_CLERK'
            ],
            'send_back_to_clerk' => [
                'from' => 'wait_for_hc_rejection',
                'to' => 'draft',
                'label' => 'Send Back to Clerk',
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
        return ['draft'];
    }
}