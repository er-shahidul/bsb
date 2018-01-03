<?php

namespace AccountBundle\EventSubscriber;

use AccountBundle\Entity\SanctionPayment;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\FilterResponseEvent;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SanctionPaymentWorkflowSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.view.event' => array(
                array('sanctionPaymentView', 10),
            ),
            'workflow.sanction_payment_workflow.task.label' => array(
                array('getWorkflowLabel', 10)
            ),
        );
    }

    public function getWorkflowLabel(FilterResponseEvent $event)
    {
        /** @var SanctionPayment $sanctionPayment */
        $sanctionPayment = $this->em->getRepository($event->getTaskQueue()->getEntity())->find($event->getTaskQueue()->getRefId());

        $msg = 'Sanction Payment';
        if (!$sanctionPayment) {
            $event->setResponse($msg);
        }

        return $event->setResponse(sprintf('%s - %s', $msg, $sanctionPayment->getNoteSheetNumber()));
    }

    public function sanctionPaymentView(GetResponseWorkflowEvent $event)
    {
        if (!$event->getEntity() instanceof SanctionPayment) {
            return;
        }

        $builder = new ResponseBuilderData('@Account/SanctionEntry/payment-view.html.twig',
            ['sanctionPayment' => $event->getEntity()]
        );

        $event->setResponseBuilder($builder);
    }
}