<?php

namespace MovementBundle\Workflow;

use AppBundle\Workflow\GenericWorkflow;
use Devnet\WorkflowBundle\Core\WorkflowDefinition;
use MovementBundle\Entity\GeneralMovement;

class GeneralMovementWorkflow extends WorkflowDefinition
{
    public static function getSupports()
    {
        return [GeneralMovement::class];
    }

    public static function getTransitionsConfig()
    {
        $data = GenericWorkflow::getTransitionsConfig();
        $data['forward_to_head_clerk']['role'] = 'ESTABLISHMENT_CLERK';

        return $data;
    }

    public static function getName()
    {
        return 'general_movement';
    }

    public static function getEditablePlaces()
    {
        return ['draft'];
    }
}