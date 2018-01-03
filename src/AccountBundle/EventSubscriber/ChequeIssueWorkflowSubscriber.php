<?php

namespace AccountBundle\EventSubscriber;

use AccountBundle\Entity\ChequeIssue;
use AccountBundle\Entity\Voucher;
use AccountBundle\Manager\ChequeIssueManager;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\FilterResponseEvent;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class ChequeIssueWorkflowSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var ChequeIssueManager */
    protected $chequeIssueManage;

    public function __construct(EntityManagerInterface $entityManager, ChequeIssueManager $chequeIssueManager)
    {
        $this->em = $entityManager;
        $this->chequeIssueManage = $chequeIssueManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.view.event' => array(
                array('chequeIssueView', 10),
            ),
            'workflow.cheque_issue.task.label' => array(
                array('getWorkflowLabel', 10),
            ),
            'workflow.cheque_issue.entered.approved' => array(
                array('updateVoucherStatus', 10),
            ),
        );
    }

    public function getWorkflowLabel(FilterResponseEvent $event)
    {
        /** @var ChequeIssue $chequeIssue */
        //$chequeIssue = $this->em->getRepository($event->getTaskQueue()->getEntity())->find($event->getTaskQueue()->getRefId());

        $event->setResponse('Cheque Issue');
    }

    public function chequeIssueView(GetResponseWorkflowEvent $event)
    {
        $chequeIssue = $event->getEntity();
        if (!$chequeIssue instanceof ChequeIssue) {
            return;
        }

        $this->chequeIssueManage->prepareVoucherDetail($chequeIssue);

        $builder = new ResponseBuilderData('AccountBundle:ChequeIssue:view.html.twig',
            array(
                'chequeIssue' => $chequeIssue,
                'fundHeads' => $this->em->getRepository('AccountBundle:FundHead')->fundHeadByFundType($chequeIssue->getFundType(), $chequeIssue->getOffice()->getOfficeType()),
                'bankAccounts' => $this->em->getRepository('AccountBundle:BankAccount')->findBy(['fundType' => $chequeIssue->getFundType()]),
                'fundHeadBalance' => $this->chequeIssueManage->getFundHeadBalanceByFundType($chequeIssue->getFundType(), $chequeIssue->getVouchers()),
            )
        );

        $event->setResponseBuilder($builder);
    }

    public function updateVoucherStatus(Event $event)
    {
        $chequeIssue = $event->getSubject();
        if (!$chequeIssue instanceof ChequeIssue) {
            return;
        }

        /** @var Voucher $voucher */
        foreach ($chequeIssue->getVouchers() as $voucher) {
            $voucher->setStatus('approved');
        }

        $this->em->flush();
    }
}