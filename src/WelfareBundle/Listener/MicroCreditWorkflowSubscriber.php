<?php

namespace WelfareBundle\Listener;

use DateInterval;
use DateTime;
use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use PersonnelBundle\Entity\FundReceived;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Transition;
use WelfareBundle\Entity\BoardRecommendation;
use WelfareBundle\Entity\MicroCreditApplication;
use WelfareBundle\Entity\MCInstallment;

class MicroCreditWorkflowSubscriber extends WelfareWorkflowSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;
    protected $dispatcher;
    protected $policyManager;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher, PolicyManager $policyManager)
    {
        parent::__construct($entityManager);
        $this->em = $entityManager;
        $this->dispatcher = $dispatcher;
        $this->policyManager = $policyManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.view.event' => array(
                array('applicationViewMicroCredit', 10),
            ),
            'granted_application' => array(
                array('recommendApplication', 10),
            ),
            'workflow.welfare_board_recommendation.entered.completed' => array(
                array('grantRecommendedApplications', 10),
            ),
        );
    }

    public function applicationViewMicroCredit(GetResponseWorkflowEvent $event)
    {
        if (!$event->getEntity() instanceof MicroCreditApplication) {
            return;
        }

        $this->applicationView($event, '@Welfare/MicroCredit/view.html.twig');
    }

    public function recommendApplication(GenericEvent $event) {
        /** @var WelfareApplication $application  */
        $application = $event->getSubject();

        if (!$application instanceof MicroCreditApplication) {
            return;
        }

        $installmentFreeMonths = $this->policyManager->getPolicyValue('welfare.micro_credit_payment_free_month_count');
        $noOfInstallments = $this->numberOfInstallments($application->getAmount(), $application->getMicroCreditDetail()->getInstallmentAmount());

        $application->getMicroCreditDetail()->setLastInstallmentAmount($application->getMicroCreditDetail()->getInstallmentAmount());
        $application->getMicroCreditDetail()->setInstallmentFreeMonths($installmentFreeMonths);
        $application->getMicroCreditDetail()->setNoOfInstallments($noOfInstallments);
        $application->setStatus('recommended');
    }

    public function grantRecommendedApplications($event) {
        /** @var BoardRecommendation $recommendation  */
        $recommendation = $event->getSubject();

        if (!$recommendation instanceof BoardRecommendation) {
            return;
        }

        $meeting = $recommendation->getMeeting();
        $applications = $this->em->getRepository('WelfareBundle:MicroCreditApplication')->findBy(array(
            'meeting' => $meeting, 'status' => 'recommended'));

        foreach ($applications as $application) {

            $application->setGrantStatus('granted');
            $application->setGrantedAt(new \DateTime());
            $application->setStatus('completed');

            $installmentStartDate = null;
            $installments = $this->createLoanInstallments($application->getAmount(), $application->getMicroCreditDetail()->getInstallmentAmount(),
                new \DateTime(), $application->getMicroCreditDetail()->getInstallmentFreeMonths());
            if (count($installments)) {
                $installmentStartDate = $this->firstDate($installments[1][0]);
                foreach ($installments as $key=>$value) {
                    $installment = new MCInstallment();
                    $installment->setDueDate($installments[$key][0]);
                    $installment->setInstallmentAmount($installments[$key][1]);
                    $installment->setOffice($application->getOffice());
                    $installment->setApplication($application);
                    $installment->setInstallmentNumber($key);
                    $this->em->persist($installment);
                }
            }
            $application->getMicroCreditDetail()->setInstallmentStartDate($installmentStartDate);

        }
        $this->em->flush();
    }


}