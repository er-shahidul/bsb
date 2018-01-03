<?php

namespace WelfareBundle\Listener;

use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\FilterResponseEvent;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Workflow\Registry;
use WelfareBundle\Entity\MCDefaulter;
use WelfareBundle\Entity\MCDefaulterRegister;
use WelfareBundle\Entity\MCReassessInstallment;
use WelfareBundle\Entity\MicroCreditPayment;

class MicroCreditPaymentSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;
    protected $dispatcher;
    protected $twigEngine;
    /**
     * @var Registry
     */
    private $workflow;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher, Registry $workflow, EngineInterface $twigEngine)
    {
        $this->em = $entityManager;
        $this->dispatcher = $dispatcher;
        $this->workflow = $workflow;
        $this->twigEngine = $twigEngine;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.view.event' => array(
                array('paymentView', 10),
                array('defaulterRegisterView', 10),
            ),
            'workflow.mc_defaulter_register.entered.approved' => array(
                array('setDefaulters', 10),
            ),
            'workflow.welfare_micro_credit_payment.entered.approved' => array(
                array('updateTotalPaid', 10),
            ),
            'workflow.mc_reassess_installment.task.label' => array(
                array('getReassessInstallmentWorkflowLabel', 10)
            ),
        );
    }

    public function paymentView(GetResponseWorkflowEvent $event)
    {
        if (!$event->getEntity() instanceof MicroCreditPayment) {
            return;
        }

        $payment = $event->getEntity();

        $data['application'] = $payment->getApplication();
        $data['payment'] = $payment;
        $data['payments'] = $this->em->getRepository(
        'WelfareBundle:MicroCreditPayment')->paymentHistory($payment->getApplication());

        $builder = new ResponseBuilderData('WelfareBundle:MicroCreditPayment:view.html.twig', $data);
        $event->setResponseBuilder($builder);
    }

    public function defaulterRegisterView(GetResponseWorkflowEvent $event)
    {
        if (!$event->getEntity() instanceof MCDefaulterRegister) {
            return;
        }

        $register = $event->getEntity();

        $data['register'] = $register;
        $data['defaulterListView'] = $this->getDefaulterList($register->getDefaulterRemarks(), false);
        $builder = new ResponseBuilderData('WelfareBundle:MCDefaulterRegister:view.html.twig', $data);
        $event->setResponseBuilder($builder);
    }

    public function getDefaulterList($ids = [], $checkable = true) {
        $results = $this->em->getRepository('WelfareBundle:MicroCreditPayment')->getDefaultersByIds($ids);
        return $this->defaulterListView($results, $checkable);
    }

    public function defaulterListView($results, $checkable = true) {
        $rows = '';
        if (count($results)) {
            foreach ($results as $result) {
                $rows .= $this->twigEngine->render('WelfareBundle:MCDefaulterRegister:defaulter_row.html.twig', [
                        'application' => $result[0],
                        'checkable' => $checkable,
                        'totalPayable' => $result['totalPayable']
                    ]
                );
            }
        } else {
            $rows = ' <tr><td colspan="11" class="text-center"></td></tr> ';
        }
        return $this->twigEngine->render('WelfareBundle:MCDefaulterRegister:defaulter_table.html.twig', [
            'rows' => $rows,
            'checkable' => $checkable,
        ]);
    }

    public function setDefaulters($event) {
        /** @var MCDefaulterRegister $register  */
        $register = $event->getSubject();

        if (!$register instanceof MCDefaulterRegister) {
            return;
        }

        $ids = $register->getDefaulterRemarks();
        foreach ($ids as $id) {
            $application = $this->em->getRepository('WelfareBundle:MicroCreditApplication')->find($id);
            $defaulter = new MCDefaulter();
            $defaulter->setApplication($application);
            $defaulter->setDefaulterRegister($register);
            $this->em->persist($defaulter);

            $reassessInstallment = new MCReassessInstallment();
            $reassessInstallment->setOffice($application->getOffice());
            $reassessInstallment->setApplication($application);
            $reassessInstallment->setStatus('create');
            $reassessInstallment->setNote('');

            $this->em->persist($reassessInstallment);
            $this->em->flush($reassessInstallment);
            $this->workflow->get($reassessInstallment)->apply($reassessInstallment, 'forward_to_dasb_clerk');

        }
        $this->em->flush();
    }

    public function getReassessInstallmentWorkflowLabel(FilterResponseEvent $event) {
        $event->setResponse('Micro-credit Defaulter Investigation');
    }

    public function updateTotalPaid($event)
    {
        /** @var MicroCreditPayment $payment */
        $payment = $event->getSubject();

        if (!$payment instanceof MicroCreditPayment) {
            return;
        }

        $application = $payment->getApplication();
        $totalPaid = $application->getMicroCreditDetail()->getTotalPaid() + $payment->getPaymentAmount();
        $application->getMicroCreditDetail()->setTotalPaid($totalPaid);

        if ($application->getAmount() == $totalPaid) {
            $application->getMicroCreditDetail()->setLoanCompleted(true);
        }
        $this->em->flush();
    }
}
