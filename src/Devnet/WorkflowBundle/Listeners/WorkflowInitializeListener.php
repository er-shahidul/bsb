<?php

namespace Devnet\WorkflowBundle\Listeners;

use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Exception\InvalidArgumentException;
use Symfony\Component\Workflow\Registry as WorkflowRegistry;
use Symfony\Component\Workflow\Transition;

class WorkflowInitializeListener
{
    /**
     * @var WorkflowRegistry
     */
    private $workflow;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(WorkflowRegistry $workflow, EventDispatcherInterface $eventDispatcher)
    {
        $this->workflow = $workflow;
        $this->dispatcher = $eventDispatcher;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof BaseWorkflowEntity) {
            return;
        }

        try {
            $workflow = $this->workflow->get($entity);
        } catch (InvalidArgumentException $exception) {
            return;
        }

        $marking = $workflow->getMarking($entity);
        $places = $marking->getPlaces();
        $transition = null;

        foreach ($places as $place => $v) {
            $transition = new Transition('_initialize', $place, $place);
        }

        if($transition == null) {
            return;
        }

        $this->dispatcher->dispatch('workflow.entered',
            new Event($entity, $marking, $transition, $workflow->getName())
        );
    }
}