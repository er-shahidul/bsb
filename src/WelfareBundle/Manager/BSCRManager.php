<?php

namespace WelfareBundle\Manager;

use AppBundle\Entity\Office;
use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use PersonnelBundle\Entity\ExServiceman;
use WelfareBundle\Entity\BSCRApplication;

class BSCRManager extends WelfareManager
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
        $data['lastReceivedFund'] = $this->lastBSCRFund($exServiceMan);
        $data['spouseInfo'] = $exServiceMan->getSpouse();
        $data['familyMembers'] = $exServiceMan->getFamilies();
        return $data;
    }

    private function policyCheck(ExServiceman $exServiceMan)
    {
        $application = $this->em->getRepository('WelfareBundle:BSCRApplication')->getLastApplicationOfServiceMan($exServiceMan);

        if ($application !== null && $application->getStatus() != 'completed') {
            return sprintf('An application is already under processing. Application date: %s', $application->getCreatedAt()->format('d-m-Y'));
        }

        $year = $this->policyManager->getPolicyValue('welfare.bscr_single_time_consecutive_year', Type::INTEGER);
        if (!$year) {
            return true;
        }

        $fund = $this->lastBSCRFund($exServiceMan);
        if (!$fund) {
            return true;
        }

        $dateDiff = (new \DateTime())->diff($fund->getDate());
        if ($dateDiff->y < $year) {
            return sprintf('%s has taken BSCR grant within last %s years. Last grant date is %s', $exServiceMan->getName(), $year, $fund->getDate()->format('d-m-Y'));
        }

        return true;
    }

    /**
     * @param ExServiceman $exServiceMan
     * @return mixed
     */
    public function lastBSCRFund(ExServiceman $exServiceMan)
    {
        return $this->lastReceivedFund('Bangladesh Serviceman Charitable Relief Fund (BSCR)', $exServiceMan);
    }

    public function getGrantPolicyText() {
        $sanction = $this->policyManager->getPolicyValue('welfare.bscr_maximum_sanction');
        $specialSanction = $this->policyManager->getPolicyValue('welfare.bscr_maximum_sanction_for_special_case');

        $str = "<strong>Maximum Grant Amount Limit: ".number_format($sanction) . " TK <br/>";
        $str .= "Maximum Grant Amount Limit for Special Case: ".number_format($specialSanction). " TK </strong><br/>";
        return $str;
    }

    public function hasNominatedApplications() {
        $row = $this->em->getRepository('WelfareBundle:BSCRApplication')->findOneBy(array(
            'status' => 'nominated'));
        return !empty($row);
    }
}