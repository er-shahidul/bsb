<?php

namespace Devnet\WorkflowBundle\Core;

class WorkflowDefinitionRegistry
{
    /** @var WorkflowDefinitionInterface[] */
    private $definitions = [];

    public function __construct($definitions = array())
    {
        $this->definitions = $definitions;
    }

    public function getTransitionRole($workflow, $transition)
    {
        return $this->getTransitionOption($workflow, $transition, 'role');
    }

    public function getTransitionLabel($workflow, $transition)
    {
        return $this->getTransitionOption($workflow, $transition, 'label');
    }

    public function getTransitionClass($workflow, $transition)
    {
        return $this->getTransitionOption($workflow, $transition, 'class');
    }

    public function getTransitionOption($workflow, $transition, $option)
    {
        return $this->getDefinition($workflow)->getTransitionConfig($transition, $option);
    }

    public function getEditablePlaces($workflow)
    {
        return $this->getDefinition($workflow)->getEditablePlaces();
    }

    public function getWorkflowIds($prefix = "")
    {
        $return = [];

        foreach ($this->definitions as $id => $value) {
            $return[] = $prefix . $id;
        }

        return $return;
    }

    /**
     * @param $id
     * @return WorkflowDefinitionInterface
     */
    public function getDefinition($id)
    {
        return isset($this->definitions[$id]) ? $this->definitions[$id] : null;
    }
}