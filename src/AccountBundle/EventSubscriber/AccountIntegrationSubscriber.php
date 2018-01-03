<?php

namespace AccountBundle\EventSubscriber;

use AccountBundle\Entity\AccountIntegration;
use AccountBundle\Entity\ChequeIssue;
use AccountBundle\Entity\FundHead;
use AccountBundle\Entity\PaymentVoucher;
use AccountBundle\Entity\SanctionPayment;
use AccountBundle\Event\AccountIntegrationEvent;
use AccountBundle\Manager\AccountIntegrationManager;
use AccountBundle\Mapper\Sanction;
use AccountBundle\Mapper\Voucher;
use AccountBundle\Mapper\VoucherDetail;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\FilterResponseEvent;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Transition;

class AccountIntegrationSubscriber implements EventSubscriberInterface
{
    /** @var EntityManager */
    protected $em;

    /**
     * @var Registry
     */
    protected $workflow;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /** @var  AccountIntegrationManager */
    protected $accountIntegrationManager;

    public function __construct(EntityManagerInterface $entityManager, Registry $registry, EventDispatcherInterface $dispatcher, AccountIntegrationManager $accountIntegrationManager)
    {
        $this->em = $entityManager;
        $this->workflow = $registry;
        $this->dispatcher = $dispatcher;
        $this->accountIntegrationManager = $accountIntegrationManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            AccountIntegrationEvent::ACCOUNT_MAKE_PAYMENT_EVENT => array(
                array('makePayment', 10),
            ),
            'workflow.view.event' => array(
                array('view', 10),
            ),
            'workflow.account_integration_workflow.task.label' => array(
                array('getWorkflowLabel', 10),
            ),
            'workflow.account_integration_workflow.entered.approved' => array(
                array('createSanctionAndVouchers', 10),
            ),
            'workflow.account_integration_workflow.guard.forward_to_head_clerk' => array(
                array('preventForwardButton', 10),
            ),
        );
    }

    public function getWorkflowLabel(FilterResponseEvent $event)
    {
        /** @var AccountIntegration $ai */
        $ai = $this->em->getRepository($event->getTaskQueue()->getEntity())->find($event->getTaskQueue()->getRefId());

        $event->setResponse($ai->getData()->getWorkflowLabel());
    }

    public function view(GetResponseWorkflowEvent $event)
    {
        $ai = $event->getEntity();
        if (!$ai instanceof AccountIntegration) {
            return;
        }

        /** @var Sanction $sanction */
        $sanction = $ai->getData();
        $this->accountIntegrationManager->prepareVoucherDetail($sanction);

        $fundType = $sanction->getFundType() ? $this->em->getRepository('AccountBundle:FundType')->find($sanction->getFundType()) : null;
        $bankAccounts = $this->em->getRepository('AccountBundle:BankAccount')->getBankAccountAsArray($ai->getOffice(), $fundType);

        $builder = new ResponseBuilderData('@Account/AccountIntegration/view.html.twig',
            array(
                'ai' => $ai,
                'bankAccounts' => $bankAccounts,
                'sanction' => $sanction,
                'fundType' => $fundType,
                'fundHeads' => !$fundType ? [] : $this->em->getRepository('AccountBundle:FundHead')->fundHeadByFundType($fundType, $ai->getOffice()->getOfficeType()),
            )
        );

        $event->setResponseBuilder($builder);
    }

    public function makePayment(AccountIntegrationEvent $event)
    {
        $entity = new AccountIntegration();
        $entity->setOffice($event->getOffice());
        $entity->setData($event->getEntity());

        $this->em->persist($entity);
        $this->em->flush();

        $workflow = $this->workflow->get($entity, 'account_integration_workflow');

        $transition = new Transition(
            'hidden_transaction',
            'draft',
            'draft'
        );
        $this->dispatcher->dispatch('workflow.entered',
            new Event($entity, $workflow->getMarking($entity), $transition, $workflow->getName())
        );
    }

    public function createSanctionAndVouchers(Event $event)
    {
        /** @var AccountIntegration $ai */
        $ai = $event->getSubject();

        /** @var Sanction */
        $sanction = $ai->getData();

        $fundType = $this->em->getReference('AccountBundle:FundType', $sanction->getFundType());
        $sanctionEntry = new SanctionPayment();
        $voucherDate = new \DateTime($sanction->getVoucherDate());
        $sanctionEntry->setVoucherDate($voucherDate);
        $sanctionEntry->setOffice($ai->getOffice());
        $sanctionEntry->setFundType($fundType);
        $sanctionEntry->setAmount(0);
        $sanctionEntry->setDescription($sanction->getDescription());
        $sanctionEntry->setStatus('approved');
        $sanctionEntry->setYear($voucherDate->format('Y'));
        $sanctionAmount = 0;
        $this->em->persist($sanctionEntry);
        $this->em->flush($sanctionEntry);

        $chequeIssue = new ChequeIssue();
        $chequeIssue->setChequeDate($voucherDate);
        $chequeIssue->setFundType($fundType);
        $chequeIssue->setStatus('approved');
        $chequeIssue->setOffice($ai->getOffice());
        $chequeIssue->addSanction($sanctionEntry);
        $this->em->persist($chequeIssue);

        /** @var Voucher $voucher */
        foreach ($sanction->getVouchers() as $voucher) {
            $bankAccount = $this->em->getReference('AccountBundle:BankAccount', $voucher->getBankAccount());
            $paymentVoucher = new PaymentVoucher();
            $paymentVoucher->setYear($voucherDate->format('Y'));
            $paymentVoucher->setChequeDate($voucherDate);
            $paymentVoucher->setChequeNumber($voucher->getChequeNumber());
            $paymentVoucher->setStatus('approved');
            $paymentVoucher->setVoucherDate(new \DateTime());
            $paymentVoucher->setDescription($voucher->getDescription());
            $paymentVoucher->setAmount($voucher->getAmount());
            $paymentVoucher->setFundType($fundType);
            $paymentVoucher->setPaymentTo($voucher->getPaymentTo());
            $paymentVoucher->setAgainst((string)$voucher->getPaymentAgainst());
            $paymentVoucher->setAccount($bankAccount);
            $paymentVoucher->setOffice($ai->getOffice());
            $paymentVoucher->setSanctions(new ArrayCollection([$sanctionEntry]));

            $this->em->persist($paymentVoucher);
            $this->em->flush($paymentVoucher);

            $chequeIssue->addVoucher($paymentVoucher);

            /** @var VoucherDetail $voucherDetail */
            foreach ($voucher->getVoucherDetails() as $voucherDetail) {
                if (!$voucherDetail->getAmount()) {
                    continue;
                }
                $paymentVoucherDetail = new \AccountBundle\Entity\VoucherDetail();
                $paymentVoucherDetail->setAmount($voucherDetail->getAmount());
                $paymentVoucherDetail->setFundHead(
                    $this->em->getReference(FundHead::class, $voucherDetail->getFundHead())
                );

                $paymentVoucherDetail->setVoucher($paymentVoucher);
                $this->em->persist($paymentVoucherDetail);
            }

            $sanctionAmount += $voucher->getAmount();
        }

        $sanctionEntry->setAmount($sanctionAmount);

        $this->em->flush();
    }

    public function preventForwardButton(GuardEvent $event)
    {
        /** @var AccountIntegration $ac */
        $ac = $event->getSubject();

        $event->setBlocked(!$ac->getData()->getVoucherDate());
    }
}