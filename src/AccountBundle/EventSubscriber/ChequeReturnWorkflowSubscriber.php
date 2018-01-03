<?php

namespace AccountBundle\EventSubscriber;

use AccountBundle\Entity\ChequeReturn;
use AccountBundle\Entity\ReceiveVoucher;
use AccountBundle\Entity\Voucher;
use AccountBundle\Entity\VoucherDetail;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\FilterResponseEvent;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Transition;

class ChequeReturnWorkflowSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var Registry */
    protected $workflow;

    /** @var EventDispatcherInterface  */
    protected $dispatcher;

    public function __construct(EntityManagerInterface $entityManager, Registry $registry, EventDispatcherInterface $dispatcher)
    {
        $this->em = $entityManager;
        $this->workflow = $registry;
        $this->dispatcher = $dispatcher;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.view.event' => array(
                array('reconciliationView', 10),
            ),
            'workflow.cheque_return_workflow.task.label' => array(
                array('getWorkflowLabel', 10),
            ),
            'workflow.cheque_return_workflow.entered.approved' => array(
                array('reconciledVoucher', 10),
            ),
        );
    }

    public function getWorkflowLabel(FilterResponseEvent $event)
    {
        /** @var ChequeReturn $reconciliation */
        $reconciliation = $this->em->getRepository($event->getTaskQueue()->getEntity())->find($event->getTaskQueue()->getRefId());

        $msg = 'Cheque Return';
        if (!$reconciliation) {
            $event->setResponse($msg);
        }

        $event->setResponse(sprintf('%s - %s', $msg, $reconciliation->getFundType()->getName()));
    }

    public function reconciliationView(GetResponseWorkflowEvent $event)
    {
        if (!$event->getEntity() instanceof ChequeReturn) {
            return;
        }

        $builder = new ResponseBuilderData('@Account/ChequeReturn/view.html.twig',
            ['reconciliation' => $event->getEntity()]
        );

        $event->setResponseBuilder($builder);
    }

    public function reconciledVoucher(Event $event)
    {
        /** @var ChequeReturn $reconciliation */
        $reconciliation = $event->getSubject();

        /** @var Voucher $voucher */
        foreach ($reconciliation->getVouchers() as $voucher) {
            $this->persisReceiveVoucher($voucher);
        }
    }

    protected function persisReceiveVoucher(Voucher $voucher)
    {
        $voucher->setReconciled(true);
        $voucher->setDebited(false);

        $receive = new ReceiveVoucher();
        $receive->setAmount($voucher->getAmount());
        $receive->setOffice($voucher->getOffice());
        $receive->setVoucherDate(new \DateTime());
        $receive->setFundType($voucher->getFundType());
        $receive->setAccount($voucher->getAccount());
        $receive->setYear($voucher->getYear());
        $receive->setToOrFrom($voucher->getToOrFrom());
        $receive->setAgainst($voucher->getAgainst());
        $receive->setDescription('Cheque Return: ' . $voucher->getChequeNumber());

        /** @var VoucherDetail $detail */
        foreach ($voucher->getVoucherDetails() as $detail) {
            $vd = new VoucherDetail();
            $vd->setAmount($detail->getAmount());
            $vd->setFundHead($detail->getFundHead());
            $vd->setVoucher($receive);
            $this->em->persist($vd);
        }

        $this->em->persist($receive);
        $this->em->flush();

        $workflow = $this->workflow->get($receive, 'receive_voucher');
        $transition = new Transition(
            'hidden_transaction',
            'draft',
            'draft'
        );
        $this->dispatcher->dispatch('workflow.entered',
            new Event($receive, $workflow->getMarking($receive), $transition, $workflow->getName())
        );
    }
}