<?php

namespace WelfareBundle\Manager;

use AppBundle\Entity\Office;
use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use PersonnelBundle\Entity\ExServiceman;
use WelfareBundle\Entity\BSCRApplication;
use WelfareBundle\Entity\WelfareApplication;

class WelfareManager extends BaseWelfareManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var PolicyManager
     */
    protected $policyManager;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository|\WelfareBundle\Repository\WelfareApplicationRepository
     */
    protected $welfareRepo;

    public function __construct(EntityManagerInterface $entityManager, PolicyManager $policyManager)
    {
        parent::__construct($entityManager);

        $this->em = $entityManager;
        $this->policyManager = $policyManager;
        $this->welfareRepo = $this->em->getRepository('WelfareBundle:WelfareApplication');
    }

    public function initApplication(WelfareApplication $application)
    {
        $exServiceman = $application->getServiceMan();

        $application->setOffice($exServiceman->getOffice());
        $application->setApplicant($exServiceman->isDeceased() ? 'Spouse' : 'Self');
        $application->setStatus('draft');
        $this->welfareRepo->save($application);
    }

    public function getDefaultFormData(WelfareApplication $application) {

        $serviceMan = $application->getServiceMan();

        $appData['name'] = $serviceMan->getName();
        $appData['dateOfBirth'] = ($serviceMan->getDateOfBirth()) ? $serviceMan->getDateOfBirth()->format('Y-m-d') : null;
        $appData['fathersName'] = $serviceMan->getFathersName();
        $appData['mothersName'] = $serviceMan->getMothersName();
        $appData['PermanentAddress']['village'] = $serviceMan->getPermanentVillage();
        $appData['PermanentAddress']['postOffice'] = $serviceMan->getPermanentPostOffice();
        $appData['PermanentAddress']['upazila'] = ($serviceMan->getPermanentUpazila()) ? $serviceMan->getPermanentUpazila()->getName() : '';
        $appData['PermanentAddress']['district'] = ($serviceMan->getPermanentDistrict()) ? $serviceMan->getPermanentDistrict()->getName() : '';
        $appData['presentAddress']['village'] = $serviceMan->getVillage();
        $appData['presentAddress']['postOffice'] = $serviceMan->getPostOffice();
        $appData['presentAddress']['upazila'] = ($serviceMan->getUpazila()) ? $serviceMan->getUpazila()->getName() : '';
        $appData['presentAddress']['district'] = ($serviceMan->getDistrict()) ? $serviceMan->getDistrict()->getName() : '';
        $appData['regimentalNumber'] = $serviceMan->getRegimentalNumber();
        $appData['corp'] = ($serviceMan->getCorp()) ? $serviceMan->getCorp()->getName() : '';
        $appData['rank'] = ($serviceMan->getRank()) ? $serviceMan->getRank()->getName() : '';
        $appData['identificationMark'] = $serviceMan->getIdentificationMark();
        $appData['serviceId'] = $serviceMan->getIdentityNumber();
        $appData['lastServedUnit'] = $serviceMan->getLastServedUnit();
        $appData['joiningDate'] = ($serviceMan->getJoiningDate()) ? $serviceMan->getJoiningDate()->format('Y-m-d') : null;
        $appData['retirementDate'] = ($serviceMan->getRetirementDate()) ? $serviceMan->getRetirementDate()->format('Y-m-d') : null;
        $appData['retirementReason'] = $serviceMan->getRetirementReason()->getId();
        $appData['pensionRate'] = $serviceMan->getPensionRate();
        $appData['pensionInfo'] = $serviceMan->getAdditionalInfo()->getPensionInfo();
        $appData['tsNumber'] = $serviceMan->getTsNumber();
        $appData['commutationInfo'] = $serviceMan->getAdditionalInfo()->getCommutationInfo();
        $appData['hasUNMission'] = $serviceMan->isUnMission();
        $appData['missionIncome'] = $serviceMan->getAdditionalInfo()->getMissionIncome();
        $appData['fixedOrCurrentAsset'] = $serviceMan->getAdditionalInfo()->hasFixedOrCurrentAsset();
        $appData['amountOfLand'] = $serviceMan->getAdditionalInfo()->getAmountOfLand();
        $appData['assetInfo'] = $serviceMan->getAdditionalInfo()->getAssetInfo();
        $appData['inheritOccupation'] = $serviceMan->getAdditionalInfo()->getInheritOccupation();
        $appData['hasBSCRGrant'] = '';
        $appData['BSCRGrantAmount'] = '';
        $appData['spouseName'] = ($serviceMan->getSpouse()) ? $serviceMan->getSpouse()->getName() : '';

        $familyMembers = $serviceMan->getFamilies();
        if ($familyMembers) {
            foreach ($familyMembers as $key=>$member) {
                if (in_array(strtolower($member->getRelationType()->getId()), ['son', 'daughter'])) {
                    $appData['familyMembers'][$key] = [
                        'name' => $member->getName(),
                        'relation' => $member->getRelationType()->getId(),
                        'dateOfBirth' => $member->getDateOfBirth()->format('Y-m-d'),
                        'occupation' => $member->getOccupation(),
                    ];
                }
            }
        }

        $appData['childrenInfo'] = $serviceMan->getAdditionalInfo()->getChildrenInfo();
        $appData['inheritName'] = $serviceMan->getInheritName();
        $appData['relationshipWithSoldier'] = $serviceMan->getInheritRelation();
        $appData['inheritPermanentAddress']['village'] = $serviceMan->getAdditionalInfo()->getInheritPermanentVillage();
        $appData['inheritPermanentAddress']['postOffice'] = $serviceMan->getAdditionalInfo()->getInheritPermanentPostOffice();
        $appData['inheritPermanentAddress']['upazila'] = ($serviceMan->getAdditionalInfo()->getInheritPermanentUpazila()) ? $serviceMan->getAdditionalInfo()->getInheritPermanentUpazila()->getName() : '';
        $appData['inheritPermanentAddress']['district'] = ($serviceMan->getAdditionalInfo()->getInheritPermanentDistrict()) ? $serviceMan->getAdditionalInfo()->getInheritPermanentDistrict()->getName() : '';
        $appData['inheritAddress']['village'] = $serviceMan->getAdditionalInfo()->getInheritVillage();
        $appData['inheritAddress']['postOffice'] = $serviceMan->getAdditionalInfo()->getInheritPostOffice();
        $appData['inheritAddress']['upazila'] = ($serviceMan->getAdditionalInfo()->getInheritUpazila()) ? $serviceMan->getAdditionalInfo()->getInheritUpazila()->getName() : '';
        $appData['inheritAddress']['district'] = ($serviceMan->getAdditionalInfo()->getInheritDistrict()) ? $serviceMan->getAdditionalInfo()->getInheritDistrict()->getName() : '';
        $appData['deceasedDate'] = ($serviceMan->getDeceasedDate()) ? $serviceMan->getDeceasedDate()->format('Y-m-d') : null;
        $appData['deceasedPlace'] = $serviceMan->getDeceasedPlace();
        $appData['deceasedReason'] = $serviceMan->getDeceasedReason();

        $officials = ['io', 'secretary', 'ao', 'dd', 'director'];
        $extra = [];
        foreach ($officials as $official) {
            $extra[$official.'Remark'] = ['name' => '', 'rank' => '', 'remark' => '', 'date' => '', 'office' => ''];
        }

        $appData['extra'] = $extra;

        return $appData;
    }

    public function prepareFormData($appData, $loggedInUserRoles = []) {

        if ($appData['dateOfBirth']) {
            $appData['dateOfBirth'] = new \DateTime($appData['dateOfBirth']);
        }
        if ($appData['joiningDate']) {
            $appData['joiningDate'] = new \DateTime($appData['joiningDate']);
        }
        if ($appData['retirementDate']) {
            $appData['retirementDate'] = new \DateTime($appData['retirementDate']);
        }
        if ($appData['deceasedDate']) {
            $appData['deceasedDate'] = new \DateTime($appData['deceasedDate']);
        }
        if (isset($appData['familyMembers'])) {
            $familyMembers = $appData['familyMembers'];
            foreach ($familyMembers as $key=>$member) {
                $member['dateOfBirth'] = new \DateTime($member['dateOfBirth']);
                $appData['familyMembers'][$key] = $member;
            }
        }

        $roles = [];
        foreach ($loggedInUserRoles as $role) {
            $roles[] = $role->getRole();
        }

        if (in_array('ROLE_SECRETARY', $roles)) {
            $appData['visibleSections']['secretary'] = 'secretary';
        } else if (in_array('ROLE_WELFARE_CLERK', $roles)) {
            $appData['visibleSections']['secretary'] = 'secretary';
        } else if (in_array('ROLE_WELFARE_CLERK', $roles)) {
            $appData['visibleSections']['secretary'] = 'secretary';
        } else if (in_array('ROLE_HEAD_CLERK', $roles)) {
            $appData['visibleSections']['secretary'] = 'secretary';
        } else if (in_array('ROLE_HEAD_CLERK', $roles)) {
            $appData['visibleSections']['secretary'] = 'secretary';
        } else if (in_array('ROLE_AO', $roles)) {
            $appData['visibleSections']['secretary'] = 'secretary';
            $appData['visibleSections']['ao'] = 'ao';
        } else if (in_array('ROLE_DD', $roles)) {
            $appData['visibleSections']['secretary'] = 'secretary';
            $appData['visibleSections']['ao'] = 'ao';
            $appData['visibleSections']['dd'] = 'dd';
        } else if (in_array('ROLE_DIRECTOR', $roles)) {
            $appData['visibleSections']['secretary'] = 'secretary';
            $appData['visibleSections']['ao'] = 'ao';
            $appData['visibleSections']['dd'] = 'dd';
            $appData['visibleSections']['director'] = 'director';
        } else if (in_array('ROLE_DASB_CLERK', $roles)) {

        } else {
            $appData['visibleSections']['secretary'] = 'secretary';
            $appData['visibleSections']['ao'] = 'ao';
            $appData['visibleSections']['dd'] = 'dd';
            $appData['visibleSections']['director'] = 'director';
        }
        return $appData;
    }
}