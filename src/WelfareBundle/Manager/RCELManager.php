<?php

namespace WelfareBundle\Manager;

use AppBundle\Entity\Office;
use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use PersonnelBundle\Entity\ExServiceman;

class RCELManager extends WelfareManager
{
    public function __construct(EntityManagerInterface $entityManager, PolicyManager $policyManager)
    {
        parent::__construct($entityManager, $policyManager);
    }

    public function eligibleInfo($serviceId, Office $loggedInUserOffice)
    {
        $exServiceMan = $this->getServiceManByServiceId($serviceId);
        if (empty($exServiceMan)) {
            return ['isEligible' => false, 'errorMessage' => 'Soldier ID not found'];
        }

        if ($exServiceMan->getOffice() != $loggedInUserOffice) {
            return ['isEligible' => false, 'errorMessage' => sprintf('%s does not belong to %s DASB', $exServiceMan->getName(), $loggedInUserOffice->getName())];
        }

        if (strtolower($exServiceMan->getService()) != 'ex british') {
            return ['isEligible' => false, 'errorMessage' => 'Not an Ex British Army'];
        }

        $spouse = $exServiceMan->getSpouse();
        if ($exServiceMan->isDeceased() &&  empty($spouse)) {
            return ['isEligible' => false, 'errorMessage' => 'Cannot create any application for the soldier no. The reason is: The soldier is dead. System has no Spouse information of him.'];
        }

        $policyResult = $this->policyCheck($exServiceMan);
        if ($policyResult !== true) {
            return ['isEligible' => false, 'errorMessage' => $policyResult];
        }

        return ['isEligible' => true, 'errorMessage' => '', 'data' => $this->getApplicationData($exServiceMan)];
    }

    public function getApplicationData($exServiceMan) {
        $data['personnel'] = $exServiceMan;
        $data['lastReceivedFund'] = $this->lastRCELFund($exServiceMan);
        $data['spouseInfo'] = $exServiceMan->getSpouse();
        $data['familyMembers'] = $exServiceMan->getFamilies();
        return $data;
    }

    private function policyCheck(ExServiceman $exServiceMan)
    {
        $application = $this->em->getRepository('WelfareBundle:RCELApplication')->getLastApplicationOfServiceMan($exServiceMan);

        if ($application !== null && $application->getStatus() != 'completed') {
            return sprintf('An application is already under processing. Application date: %s', $application->getCreatedAt()->format('d-m-Y'));
        }

        $deadLineDate = $this->policyManager->getPolicyValue('welfare.single_time_grant_deadline', Type::DATE);

        if (!$deadLineDate) {
            return true;
        }

        $fund = $this->lastRCELFund($exServiceMan);

        if (!$fund) {
            return true;
        }

        //todo need to check
        if ($fund->getDate()->format('Y-m-d') > $deadLineDate) {
            return sprintf('%s has taken RCEL grant within deadline date %s. Last grant date is %s', $exServiceMan->getName(), $deadLineDate->format('d-m-Y'), $fund->getDate()->format('d-m-Y'));
        }

        return true;
    }

    /**
     * @param ExServiceman $exServiceMan
     * @return mixed
     */
    public function lastRCELFund(ExServiceman $exServiceMan)
    {
        return $this->lastReceivedFund('Royal Commonwealth Ex-services League (RCEL)', $exServiceMan);
    }

    public function getGrantPolicyText() {
        $sanction = $this->policyManager->getPolicyValue('welfare.rcel_grant_amount');
        return "<strong>Maximum Grant Amount Limit: ".number_format($sanction) . " TK <br/>";
    }

    public function hasNominatedApplications() {
        $row = $this->em->getRepository('WelfareBundle:RCELApplication')->findOneBy(array(
            'status' => 'nominated'));
        return !empty($row);
    }
}