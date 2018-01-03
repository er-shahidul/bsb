<?php

namespace WelfareBundle\Listener;

use DateInterval;
use DateTime;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use PersonnelBundle\Entity\FundReceived;

abstract class WelfareWorkflowSubscriber
{
    /** @var EntityManagerInterface */
    protected $em;
    protected $intervalSpec = 'P27D';

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function applicationView(GetResponseWorkflowEvent $event, $template)
    {
        $application = $event->getEntity();

        $data['application'] = $application;
        $data['personnel'] = $application->getServiceMan();
        $data['familyMembers'] = $data['personnel']->getFamilies();
        $data['selfApplicant'] = (strtolower($application->getApplicant()) == 'self') ? true :false;
        $data['spouseInfo'] = $data['personnel']->getSpouse();

        $builder = new ResponseBuilderData($template, $data);
        $event->setResponseBuilder($builder);
    }

    /**
     * @param $fundType
     * @param $application
     */
    public function createFundReceivedEntryForPersonnel($fundType, $application)
    {
        $fund = new FundReceived();
        $fund->setAmount($application->getAmount());
        $fund->setDate(new \DateTime());
        $fund->setFundType($fundType);
        $fund->setSystemGenerated(true);

        $this->em->persist($fund);

        $application->getServiceMan()->addReceivedFund($fund);
    }

    public function createLoanInstallments($grantAmount, $installmentAmount, $startDate, $installmentFreeMonths) {

        $amount = $grantAmount/$installmentAmount;
        $count = (int)$amount;
        $remainder = ($amount - $count) * $installmentAmount;
        if ($remainder) {
            $count++;
        }

        $startDate->add(new DateInterval('P'.$installmentFreeMonths.'M'));
        $installmentStart = new DateTime($startDate->format('Y').'-'.$startDate->format('m').'-01');

        $installments = [];
        for ($i=1; $i<=$count; $i++) {
            if ($remainder && $i==$count) {
                $installmentAmount = $remainder;
            }

            $installmentStart->add(new DateInterval('P1M'));
            $dueDate = clone $installmentStart;
            $dueDate->add(new DateInterval($this->intervalSpec));

            $installments[$i] = [$dueDate, $installmentAmount];
        }

        return $installments;
    }

    public function numberOfInstallments($grantAmount, $installmentAmount) {
        $amount = $grantAmount/$installmentAmount;
        $count = floor($amount);
        $remainder = ($amount - $count) * $installmentAmount;
        if ($remainder) {
            $count++;
        }
        return $count;
    }

    public function firstDate($date) {
        return new DateTime($date->format('Y').'-'.$date->format('m').'-01');
    }

    public function dueDate($firstDate) {
        return $firstDate->add(new DateInterval($this->intervalSpec));
    }
}