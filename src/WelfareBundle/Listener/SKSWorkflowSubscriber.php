<?php

namespace WelfareBundle\Listener;

use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Transition;
use WelfareBundle\Entity\SKSApplication;

class SKSWorkflowSubscriber extends WelfareWorkflowSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;
    protected $dispatcher;
    protected $registry;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher, Registry $registry)
    {
        parent::__construct($entityManager);
        $this->em = $entityManager;
        $this->dispatcher = $dispatcher;
        $this->registry = $registry;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.view.event' => array(
                array('applicationViewSKS', 10),
            ),
        );
    }

    public function applicationViewSKS(GetResponseWorkflowEvent $event)
    {
        if (!$event->getEntity() instanceof SKSApplication) {
            return;
        }

        $this->applicationView($event, '@Welfare/SKS/view.html.twig');
    }
}