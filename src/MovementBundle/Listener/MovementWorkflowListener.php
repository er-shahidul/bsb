<?php

namespace MovementBundle\Listener;


use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use MovementBundle\Entity\Movement;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MovementWorkflowListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.view.event' => array(
                array('renderView', 10),
            )
        );
    }

    public function renderView(GetResponseWorkflowEvent $event)
    {
        /** @var Movement $movement */
        $movement = $event->getEntity();
        if(!$movement instanceof Movement) {
            return;
        }
        $builder = new ResponseBuilderData(
            '@Movement/Movement/show.html.twig',
            [
                'movement' => $movement,
                'movement_type' => $movement->getType(),
                'entityClass' => get_class($movement)
            ]
        );
        $event->setResponseBuilder($builder);
    }
}