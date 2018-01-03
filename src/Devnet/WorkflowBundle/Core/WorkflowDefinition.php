<?php

namespace Devnet\WorkflowBundle\Core;

abstract class WorkflowDefinition implements WorkflowDefinitionInterface
{
    public static function getPropertyName()
    {
        return 'status';
    }

    public static function getType()
    {
        return 'workflow';
    }

    public static function getInitialPlace()
    {
        return null;
    }

    public static function getMarkingStoreConfig()
    {
        return [
            "type" => "single_state",
            "arguments" => [static::getPropertyName()]
        ];
    }


    public static function getPlaces()
    {
        $places = [];

        $transitions = static::getTransitionsConfig();

        foreach ($transitions as $transition) {
            $places = array_merge($places, static::getPlacesFromTransaction($transition));
        }

        return $places;
    }

    private static function getPlacesFromTransaction($transition)
    {
        $from = is_array($transition['from']) ? $transition['from'] : (array)$transition['from'];
        $to = is_array($transition['to']) ? $transition['to'] : (array)$transition['to'];
        return array_merge($from, $to);
    }

    final public static function getTransitionConfig($transition, $str = null)
    {
        $transitionsConfig = static::getTransitionsConfig();

        if ($str == null) {
            return $transitionsConfig[$transition];
        }
        return isset($transitionsConfig[$transition][$str]) ?
            $transitionsConfig[$transition][$str] : null;
    }

    public static function getEditablePlaces()
    {
        return [];
    }
}