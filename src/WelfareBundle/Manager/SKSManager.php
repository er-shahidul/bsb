<?php

namespace WelfareBundle\Manager;

use AppBundle\Entity\Office;
use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Doctrine\ORM\EntityManagerInterface;
use PersonnelBundle\Entity\ExServiceman;
use WelfareBundle\Entity\SKSApplication;
use WelfareBundle\Entity\SKSApplicationType;

class SKSManager extends BaseWelfareManager
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function eligibleInfo(SKSApplicationType $type, $serviceId, Office $loggedInUserOffice)
    {
        $exServiceMan = $this->getServiceManByServiceId($serviceId);
        if (empty($exServiceMan)) {
            return ['isEligible' => false, 'errorMessage' => 'Soldier ID not found'];
        }

        if ($exServiceMan->getOffice() != $loggedInUserOffice) {
            return ['isEligible' => false, 'errorMessage' => sprintf('%s does not belong to %s DASB', $exServiceMan->getName(), $loggedInUserOffice->getName())];
        }

        $policyResult = $this->policyCheck($type, $exServiceMan);
        if ($policyResult !== true) {
            return ['isEligible' => false, 'errorMessage' => $policyResult];
        }

        return ['isEligible' => true, 'errorMessage' => '', 'data' => $this->getApplicationData($type, $exServiceMan)];
    }

    public function getApplicationData($type, $exServiceMan) {
        $data['personnel'] = $exServiceMan;
        $data['lastReceivedFund'] = $this->lastSKSFund($type, $exServiceMan);
        $data['spouseInfo'] = $exServiceMan->getSpouse();
        $data['familyMembers'] = $exServiceMan->getFamilies();
        return $data;
    }

    private function policyCheck(SKSApplicationType $type, ExServiceman $exServiceMan)
    {
        $application = $this->em->getRepository('WelfareBundle:SKSApplication')->getLastApplicationOfServiceMan($type, $exServiceMan);

        if ($application !== null && $application->getStatus() != 'completed') {
            return sprintf('An application is already under processing. Application date: %s', $application->getCreatedAt()->format('d-m-Y'));
        }

        return true;
    }

    /**
     * @param ExServiceman $exServiceMan
     * @return mixed
     */
    public function lastSKSFund($type, ExServiceman $exServiceMan)
    {
        return $this->lastReceivedFund($type, $exServiceMan);
    }

    public function initApplication(SKSApplication $application)
    {
        $exServiceman = $application->getServiceMan();

        $application->setOffice($exServiceman->getOffice());
        $application->setApplicant($exServiceman->isDeceased() ? 'Spouse' : 'Self');
        $this->em->getRepository('WelfareBundle:SKSApplication')->save($application);
    }
}