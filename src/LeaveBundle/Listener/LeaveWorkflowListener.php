<?php

namespace LeaveBundle\Listener;


use LeaveBundle\Entity\Leave;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LeaveWorkflowListener implements EventSubscriberInterface
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
        /** @var Leave $leave */
        $leave = $event->getEntity();
        if(!$leave instanceof Leave) {
            return;
        }
        $builder = new ResponseBuilderData(
            '@Leave/Leave/show.html.twig',
            [
                'leave' => $leave,
                'leave_type' => $leave->getType(),
                'entityClass' => get_class($leave)
            ]
        );
        $event->setResponseBuilder($builder);
    }
}