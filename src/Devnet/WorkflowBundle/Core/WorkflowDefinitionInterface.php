<?php

namespace Devnet\WorkflowBundle\Core;

interface WorkflowDefinitionInterface
{
    public static function getSupports();
    public static function getEditablePlaces();
    public static function getName();
    public static function getInitialPlace();
    public static function getPropertyName();
    public static function getType();
    public static function getMarkingStoreConfig();
    public static function getTransitionsConfig();
    public static function getTransitionConfig($transition, $option = null);
    public static function getPlaces();
}