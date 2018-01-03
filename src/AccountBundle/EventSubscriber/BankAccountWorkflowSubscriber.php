<?php

namespace AccountBundle\EventSubscriber;

use AccountBundle\Entity\BankAccount;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\FilterResponseEvent;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BankAccountWorkflowSubscriber implements EventSubscriberInterface
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
                array('view', 10),
            ),
            'workflow.accounts_bank_account.task.label' => array(
                array('getWorkflowLabel', 10),
            )
        );
    }

    public function getWorkflowLabel(FilterResponseEvent $event)
    {
        //$voucher = $this->em->getRepository($event->getTaskQueue()->getEntity())->find($event->getTaskQueue()->getRefId());

        $event->setResponse('Bank Account');
    }

    public function view(GetResponseWorkflowEvent $event)
    {
        $bankAccount = $event->getEntity();
        if (!$bankAccount instanceof BankAccount) {
            return;
        }

        $builder = new ResponseBuilderData('@Account/BankAccount/view.html.twig',
            array(
                'bankAccount' => $bankAccount,
            )
        );

        $event->setResponseBuilder($builder);
    }
}