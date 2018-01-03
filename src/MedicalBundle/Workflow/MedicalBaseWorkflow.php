<?php

namespace MedicalBundle\Workflow;

use AppBundle\Workflow\GenericWorkflow;

abstract class MedicalBaseWorkflow extends GenericWorkflow
{
    public static function getTransitionsConfig()
    {
        $transitions = parent::getTransitionsConfig();

        unset($transitions['reject_by_director']);
        unset($transitions['reject_by_secretary']);
        $transitions['forward_to_head_clerk']['role'] = 'MEDICINE_CLERK';

        return $transitions;
    }
}