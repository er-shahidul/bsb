<?php

namespace WelfareBundle\Manager;

use AppBundle\Entity\Office;
use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use PersonnelBundle\Entity\ExServiceman;

class MicroCreditManager extends WelfareManager
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

        $policyResult = $this->policyCheck($exServiceMan);
        if ($policyResult !== true) {
            return ['isEligible' => false, 'errorMessage' => $policyResult];
        }

        return ['isEligible' => true, 'errorMessage' => '', 'data' => $this->getApplicationData($exServiceMan)];
    }

    public function getApplicationData($exServiceMan) {
        $data['personnel'] = $exServiceMan;
        $data['lastReceivedFund'] = $this->lastMicroCreditFund($exServiceMan);
        $data['spouseInfo'] = $exServiceMan->getSpouse();
        $data['familyMembers'] = $exServiceMan->getFamilies();
        return $data;
    }

    private function policyCheck(ExServiceman $exServiceMan)
    {
        $application = $this->em->getRepository('WelfareBundle:MicroCreditApplication')->getLastApplicationOfServiceMan($exServiceMan);

        if ($application !== null && $application->getStatus() != 'completed') {
            return sprintf('An application is already under processing. Application date: %s', $application->getCreatedAt()->format('d-m-Y'));
        }

        return true;
    }

    /**
     * @param ExServiceman $exServiceMan
     * @return mixed
     */
    public function lastMicroCreditFund(ExServiceman $exServiceMan)
    {
        return $this->lastReceivedFund('Micro-credit', $exServiceMan);
    }

    public function getGrantPolicyText() {
        $sanction = $this->policyManager->getPolicyValue('welfare.micro_credit_maximum_sanction');

        $str = "<strong>Maximum Grant Amount Limit: ".number_format($sanction) . " TK <br/>";
        return $str;
    }

    public function hasNominatedApplications() {
        $row = $this->em->getRepository('WelfareBundle:MicroCreditApplication')->findOneBy(array(
            'status' => 'nominated'));
        return !empty($row);
    }


}