<?php

namespace AccountBundle\EventSubscriber;

use AccountBundle\Entity\ReceiveVoucher;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\FilterResponseEvent;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class ReceiveVoucherWorkflowSubscriber implements EventSubscriberInterface
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
                array('receiveVoucherView', 10),
            ),
            'workflow.receive_voucher.task.label' => array(
                array('getWorkflowLabel', 10),
            ),
            'workflow.receive_voucher.entered.approved' => array(
                array('reconciledVoucher', 10),
            ),
        );
    }

    public function getWorkflowLabel(FilterResponseEvent $event)
    {
        /** @var ReceiveVoucher $voucher */
        //$voucher = $this->em->getRepository($event->getTaskQueue()->getEntity())->find($event->getTaskQueue()->getRefId());

        $event->setResponse('Receive Voucher');
    }

    public function receiveVoucherView(GetResponseWorkflowEvent $event)
    {
        $receiveVoucher = $event->getEntity();
        if (!$receiveVoucher instanceof ReceiveVoucher) {
            return;
        }

        $builder = new ResponseBuilderData('@Account/ReceivePayment/view.html.twig',
            array(
                'receiveVoucher' => $receiveVoucher,
            )
        );

        $event->setResponseBuilder($builder);
    }

    public function reconciledVoucher(Event $event)
    {
        /** @var ReceiveVoucher $receiveVoucher */
        $receiveVoucher = $event->getSubject();
        $receiveVoucher->setReconciled(true);
        $receiveVoucher->setReconciliationDate(new \DateTime());
        $receiveVoucher->setDebited(true);
        $receiveVoucher->setVoucherDate(new \DateTime());
        //$this->em->getRepository('AccountBundle:ReceiveVoucher')->setVoucherNumber($receiveVoucher, 'RV');

        $this->em->flush();
    }
}