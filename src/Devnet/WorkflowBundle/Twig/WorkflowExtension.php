<?php

namespace Devnet\WorkflowBundle\Twig;

use Devnet\WorkflowBundle\Core\WorkflowDefinitionRegistry;
use Devnet\WorkflowBundle\Entity\TaskQueue;
use Devnet\WorkflowBundle\Event\FilterResponseEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class WorkflowExtension extends \Twig_Extension
{
    /**
     * @var WorkflowDefinitionRegistry
     */
    private $definitionRegistry;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * WorkflowExtension constructor.
     * @param WorkflowDefinitionRegistry $definitionRegistry
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(WorkflowDefinitionRegistry $definitionRegistry, EventDispatcherInterface $eventDispatcher)
    {
        $this->definitionRegistry = $definitionRegistry;
        $this->dispatcher = $eventDispatcher;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('transition_label', array($this, 'printLabel')),
            new \Twig_SimpleFilter('transition_class', array($this, 'printClass')),
            new \Twig_SimpleFilter('transition_option', array($this, 'printOption')),
            new \Twig_SimpleFilter('workflow_label', array($this, 'printWorkflowLabel')),
        );
    }

    public function printLabel($transition, $workflowName)
    {
        return $this->definitionRegistry->getTransitionLabel($workflowName, $transition);
    }

    public function printClass($transition, $workflowName)
    {
        return $this->definitionRegistry->getTransitionClass($workflowName, $transition);
    }

    public function printOption($transition, $workflowName, $option)
    {
        if(empty($option)) {
            return '';
        }

        return $this->definitionRegistry->getTransitionOption($workflowName, $transition, $option);
    }

    public function printWorkflowLabel(TaskQueue $task)
    {
        $event = new FilterResponseEvent($task, $task->getModuleId());
        $this->dispatcher->dispatch('workflow.' . $task->getModuleId() . '.task.label', $event);
        return $event->getResponse();
    }
}