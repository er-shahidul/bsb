<?php

namespace WelfareBundle\Listener;

use BoardMeetingBundle\Entity\BoardMeeting;
use BoardMeetingBundle\Event\BoardMeetingEvent;
use DateTime;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Workflow\Registry;
use WelfareBundle\Entity\BoardRecommendation;
use WelfareBundle\Entity\BSCRApplication;
use WelfareBundle\Entity\MCInstallment;
use WelfareBundle\Entity\MCReassessInstallment;
use WelfareBundle\Entity\MicroCreditPayment;

class MCReassessInstallmentSubscriber extends WelfareWorkflowSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;
    /**
     * @var Registry
     */
    private $workflow;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->em = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.view.event' => array(
                array('reassessInstallmentView', 10),
            ),
            'workflow.mc_reassess_installment.entered.approved' => array(
                array('reassessInstallment', 10),
            ),
            'workflow.mc_reassess_installment.guard.approve_by_director' => array(
                array('preventResetAmount', 10),
            ),
        );
    }

    public function preventResetAmount($event) {

        /** @var MCReassessInstallment $reassessInstallment */
        $reassessInstallment = $event->getSubject();
        $event->setBlocked($reassessInstallment->getInstallmentAmount() < 1);
    }

    public function reassessInstallment($event)
    {
        /** @var MCReassessInstallment $reassessInstallment */
        $reassessInstallment = $event->getSubject();

        if (!$reassessInstallment instanceof MCReassessInstallment) {
            return;
        }

        $microCreditApplication = $reassessInstallment->getApplication();
        $microCreditDetail = $microCreditApplication->getMicroCreditDetail();

        $totalPaidSoFar = $microCreditDetail->getTotalPaid();
        $installmentAmount = $microCreditDetail->getLastInstallmentAmount();

        $paidCount = $totalPaidSoFar/$installmentAmount;
        $fullPaidCount = ($paidCount == 0) ? -1 : (int)$paidCount;
        $keepCount = (int)ceil($paidCount);

        if ($fullPaidCount == 0) {
            $keepCount = 1;
            $unpaidRemainder = $totalPaidSoFar;
        } else {
            $unpaidRemainder = $totalPaidSoFar - $fullPaidCount * $installmentAmount;
        }

        $installments = $this->em->getRepository('WelfareBundle:MCInstallment')->findBy([
            'application' => $microCreditApplication, 'deleted' => false], ['id' => 'asc']);
        $i = 1;
        $lastInstallmentNo = 0;
        $lastDueDate = $this->dueDate($microCreditDetail->getInstallmentStartDate());

        foreach ($installments as $installment) {

            if ($i > $keepCount) {
                $installment->setDeleted(true);
                $this->em->persist($installment);
            } else {
                if ($fullPaidCount == 0) {
                    $installment->setInstallmentAmount($unpaidRemainder);
                    $this->em->persist($installment);
                } else {
                    if (!empty($unpaidRemainder) && $i == $keepCount) {
                        $installment->setInstallmentAmount($unpaidRemainder);
                        $this->em->persist($installment);
                        $lastInstallmentNo = $installment->getInstallmentNumber();
                        $lastDueDate = $installment->getDueDate();
                    }
                }
            }
            $i++;
        }

        $lastNo = $this->resetInstallments($microCreditApplication, $microCreditDetail, $lastInstallmentNo, $lastDueDate, $reassessInstallment);

        $microCreditDetail->setInstallmentAmount($reassessInstallment->getInstallmentAmount());
        $microCreditDetail->setLastInstallmentAmount($reassessInstallment->getInstallmentAmount());
        $microCreditDetail->setNoOfInstallments($lastNo);
        $microCreditDetail->setReassessmentCount($microCreditDetail->getReassessmentCount() + 1);

        $defaulters = $this->em->getRepository('WelfareBundle:MCDefaulter')->findBy(['application' => $microCreditApplication]);
        foreach ($defaulters as $defaulter) {
            $this->em->remove($defaulter);
        }

        $this->em->flush();
    }

    private function resetInstallments($microCreditApplication, $microCreditDetail, $lastInstallmentNo, $lastDueDate, $reassessInstallment)
    {
        $lastNo = $lastInstallmentNo;
        $assessedInstallmentAmount = $reassessInstallment->getInstallmentAmount();
        $assessedGrantAmount = $microCreditApplication->getAmount() - $microCreditDetail->getTotalPaid() ;

        $newInstallments = $this->createLoanInstallments($assessedGrantAmount, $assessedInstallmentAmount, $lastDueDate, 0);
        if (count($newInstallments)) {
            foreach ($newInstallments as $key=>$value) {
                $iNumber = $key + $lastInstallmentNo;
                $newInstallment = new MCInstallment();
                $newInstallment->setDueDate($newInstallments[$key][0]);
                $newInstallment->setInstallmentAmount($newInstallments[$key][1]);
                $newInstallment->setOffice($microCreditApplication->getOffice());
                $newInstallment->setApplication($microCreditApplication);
                $newInstallment->setInstallmentNumber($iNumber+1);
                $this->em->persist($newInstallment);
                $lastNo = $iNumber+1;
            }
        }
        return $lastNo;
    }

    public function reassessInstallmentView(GetResponseWorkflowEvent $event)
    {
        if (!$event->getEntity() instanceof MCReassessInstallment) {
            return;
        }

        /** @var MCReassessInstallment $reassessInstallment */
        $reassessInstallment = $event->getEntity();

        $data['application'] = $reassessInstallment->getApplication();
        $data['reassessInstallment'] = $reassessInstallment;
        $data['payments'] = $this->em->getRepository(
            'WelfareBundle:MicroCreditPayment')->paymentHistory($reassessInstallment->getApplication());
        if ($reassessInstallment->getStatus() == 'wait_for_director') {
            $data['setInstallment'] = true;
            $builder = new ResponseBuilderData('WelfareBundle:MCReassessInstallment:approver_view.html.twig', $data);
            $event->setResponseBuilder($builder);
            return;
        }

        $builder = new ResponseBuilderData('WelfareBundle:MCReassessInstallment:view.html.twig', $data);
        $event->setResponseBuilder($builder);
    }
}
