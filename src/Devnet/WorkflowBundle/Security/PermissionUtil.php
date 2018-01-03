<?php

namespace Devnet\WorkflowBundle\Security;

use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;

class PermissionUtil
{

    private static function doCan(Marking $marking, Transition $transition)
    {
        foreach ($transition->getFroms() as $place) {
            if (!$marking->has($place)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $transitions Transition[]
     * @param Marking $marking
     * @return array
     */
    public static function getTransitionsFromCurrentMerking($transitions, Marking $marking)
    {
        $enabled = [];

        foreach ($transitions as $transition) {
            if (self::doCan($marking, $transition)) {
                $enabled[] = $transition;
            }
        }

        return $enabled;
    }
}
