<?php

namespace AccountBundle\EventSubscriber;

use AccountBundle\Entity\PaymentVoucher;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\FilterResponseEvent;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class PaymentVoucherWorkflowSubscriber implements EventSubscriberInterface
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
                array('paymentVoucherView', 10),
            ),
            'workflow.payment_voucher.task.label' => array(
                array('getWorkflowLabel', 10),
            ),
            'workflow.payment_voucher.entered.approved' => array(
                array('updateVoucherNumber', 10),
            ),
        );
    }

    public function getWorkflowLabel(FilterResponseEvent $event)
    {
        /** @var PaymentVoucher $voucher */
        $voucher = $this->em->getRepository($event->getTaskQueue()->getEntity())->find($event->getTaskQueue()->getRefId());

        $title = $voucher->getChequeNumber() ? 'Payment Voucher' : 'Miscellaneous Payment';

        $event->setResponse($title);
    }

    public function paymentVoucherView(GetResponseWorkflowEvent $event)
    {
        $paymentVoucher = $event->getEntity();
        if (!$paymentVoucher instanceof PaymentVoucher) {
            return;
        }

        $builder = new ResponseBuilderData('@Account/MiscellaneousPayment/view.html.twig',
            array(
                'paymentVoucher' => $paymentVoucher,
            )
        );

        $event->setResponseBuilder($builder);
    }

    public function updateVoucherNumber(Event $event)
    {
        /** @var PaymentVoucher $voucher */
        $voucher = $event->getSubject();

        if ($voucher->getChequeNumber()) {
            return;
        }

        $voucher->setDebited(true);
        $voucher->setReconciled(true);
        $voucher->setReconciliationDate(new \DateTime());
        $voucher->setVoucherDate(new \DateTime());
        //$this->em->getRepository('AccountBundle:PaymentVoucher')->setVoucherNumber($voucher, 'PV');

        $this->em->flush();
    }
}