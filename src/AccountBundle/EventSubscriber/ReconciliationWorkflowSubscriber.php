<?php

namespace AccountBundle\EventSubscriber;

use AccountBundle\Entity\ChequeReconciliation;
use AccountBundle\Entity\Voucher;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\FilterResponseEvent;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class ReconciliationWorkflowSubscriber implements EventSubscriberInterface
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
                array('reconciliationView', 10),
            ),
            'workflow.cheque_reconciliation_workflow.task.label' => array(
                array('getWorkflowLabel', 10),
            ),
            'workflow.cheque_reconciliation_workflow.entered.approved' => array(
                array('reconciledVoucher', 10),
            ),
        );
    }

    public function getWorkflowLabel(FilterResponseEvent $event)
    {
        /** @var ChequeReconciliation $reconciliation */
        $reconciliation = $this->em->getRepository($event->getTaskQueue()->getEntity())->find($event->getTaskQueue()->getRefId());

        $msg = 'Reconciliation';
        if (!$reconciliation) {
            $event->setResponse($msg);
        }

        return $event->setResponse(sprintf('%s - %s for %s', $msg, $reconciliation->getFundType()->getName(), $reconciliation->getMonthName()));
    }

    public function reconciliationView(GetResponseWorkflowEvent $event)
    {
        if (!$event->getEntity() instanceof ChequeReconciliation) {
            return;
        }

        $builder = new ResponseBuilderData('@Account/ChequeReconciliation/view.html.twig',
            ['reconciliation' => $event->getEntity()]
        );

        $event->setResponseBuilder($builder);
    }

    public function reconciledVoucher(Event $event)
    {
        /** @var ChequeReconciliation $reconciliation */
        $reconciliation = $event->getSubject();


        /** @var Voucher $voucher */
        foreach ($reconciliation->getVouchers() as $voucher) {

            $voucher->setReconciled(true);
            $voucher->setDebited(true);

        }

        $this->em->flush();
        $this->em->getRepository('AccountBundle:PaymentVoucher')->generateVoucherNumber($reconciliation->getOffice(), $reconciliation->getFundType(), $reconciliation->getYear(), $reconciliation->getMonth());
        $this->em->getRepository('AccountBundle:ReceiveVoucher')->generateVoucherNumber($reconciliation->getOffice(), $reconciliation->getFundType(), $reconciliation->getYear(), $reconciliation->getMonth());
    }
}