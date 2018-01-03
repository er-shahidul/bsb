<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use MedicalBundle\Entity\Dispensary;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ExServiceman
 *
 * @ORM\Table(name="ex_serviceman")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\ExServicemanRepository")
 * @UniqueEntity("identityNumber")
 */
class ExServiceman extends Personnel
{
    use MilitaryPersonnel;

    /**
     * @var bool
     *
     * @ORM\Column(name="deceased", type="boolean")
     */
    private $deceased = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="army_commando_course", type="boolean")
     */
    private $armyCommandoCourse = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="shanti_nebas", type="boolean")
     */
    private $shantiNebas = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="after_retirement_city_or_village", type="boolean")
     */
    private $afterRetirementCityOrVillage = false;

    /**
     * @var string
     *
     * @ORM\Column(name="after_retirement_living_nature", type="string", length=255, nullable=true)
     */
    private $afterRetirementLivingNature;

    /**
     * @var string
     *
     * @ORM\Column(name="after_retirement_planting_land", type="string", length=255, nullable=true)
     */
    private $afterRetirementPlantingLand;

    /**
     * @var string
     *
     * @ORM\Column(name="after_retirement_source_of_income", type="string", length=255, nullable=true)
     */
    private $afterRetirementSourceOfIncome;

    /**
     * @var bool
     *
     * @ORM\Column(name="sniper_course", type="boolean")
     */
    private $sniperCourse = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="explosion_course", type="boolean")
     */
    private $explosionCourse = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="emanation_technician", type="boolean")
     */
    private $emanationTechnician = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="bmaOrSIAndT", type="boolean")
     */
    private $bmaOrSIAndT = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deceasedDate", type="date", nullable=true)
     */
    private $deceasedDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="disabled", type="boolean")
     */
    private $disabled = false;

    /**
     * @var string
     *
     * @ORM\Column(name="disability_reason", type="string", length=512, nullable=true)
     */
    private $disabilityReason;

    /**
     * @var bool
     *
     * @ORM\Column(name="un_mission", type="boolean")
     */
    private $unMission = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="freedom_fighter", type="boolean")
     */
    private $freedomFighter = false;

    /**
     * @var string
     *
     * @ORM\Column(name="deceased_place", type="string", length=255, nullable=true)
     */
    private $deceasedPlace;

    /**
     * @var string
     *
     * @ORM\Column(name="re_employment", type="string", length=255, nullable=true)
     */
    private $reEmployment;

    /**
     * @var string
     *
     * @ORM\Column(name="deceased_reason", type="string", length=255, nullable=true)
     */
    private $deceasedReason;

    /**
     * @var string
     *
     * @ORM\Column(name="tsNumber", type="string", length=255, nullable=true)
     */
    private $tsNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="trade", type="string", length=255, nullable=true)
     */
    private $trade;

    /**
     * @var string
     *
     * @ORM\Column(name="inherit_name", type="string", length=255, nullable=true)
     */
    private $inheritName;

    /**
     * @var string
     *
     * @ORM\Column(name="inherit_nid", type="string", length=255, nullable=true)
     */
    private $inheritNID;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inherit_birth_date", type="date", nullable=true)
     */
    private $inheritBirthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="inherit_relation", type="string", length=255, nullable=true)
     */
    private $inheritRelation;

    /**
     * @var string
     *
     * @ORM\Column(name="emergency_name", type="string", length=255, nullable=true)
     */
    private $emergencyName;

    /**
     * @var string
     *
     * @ORM\Column(name="emergency_relation", type="string", length=255, nullable=true)
     */
    private $emergencyRelation;

    /**
     * @var string
     *
     * @ORM\Column(name="emergency_number", type="string", length=255, nullable=true)
     */
    private $emergencyNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="emergency_address", type="string", length=255, nullable=true)
     */
    private $emergencyAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="discipline_status", type="string", length=255, nullable=true)
     */
    private $disciplineStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reservist_last_date", type="date", nullable=true)
     */
    private $reservistLastDate;

    /**
     * @var string
     *
     * @ORM\Column(name="remarks", type="string", length=512, nullable=true)
     */
    private $remarks;

    /**
     * @var float
     *
     * @ORM\Column(name="pensionRate", type="float", nullable=true)
     */
    private $pensionRate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="retirementDate", type="date", nullable=true)
     */
    private $retirementDate;

    /**
     * @var RetirementReason
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\RetirementReason")
     */
    private $retirementReason;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\FundReceived", mappedBy="serviceman", cascade={"persist"}, orphanRemoval=true)
     */
    protected $receivedFunds;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\ExFamilyInformation", mappedBy="serviceman", cascade={"persist"}, orphanRemoval=true )
     */
    protected $families;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\ExSpecialDisease", mappedBy="serviceman", cascade={"persist"}, orphanRemoval=true )
     */
    protected $specialDiseases;

    /**
     * @ORM\OneToOne(targetEntity="PersonnelBundle\Entity\ExAdditionalInformation", mappedBy="serviceman", cascade={"persist"}, orphanRemoval=true)
     */
    private $additionalInfo;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\ExCareerInformation", mappedBy="serviceman", cascade={"persist"}, orphanRemoval=true )
     */
    protected $careers;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\ExEducationalInformation", mappedBy="serviceman", cascade={"persist"}, orphanRemoval=true )
     */
    protected $educations;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\ExServiceInformation", mappedBy="serviceman", cascade={"persist"}, orphanRemoval=true )
     */
    protected $servicesInfo;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\ExTrainingInformation", mappedBy="serviceman", cascade={"persist"}, orphanRemoval=true )
     */
    protected $trainings;

    /**
     * @var Dispensary
     *
     * @ORM\ManyToOne(targetEntity="MedicalBundle\Entity\Dispensary")
     * @ORM\JoinColumn(name="dispensary")
     */
    protected $dispensary;

    /**
     * @return string
     */
    public function getFullPostalAddress()
    {
        $address = $this->getVillage().' ';
        $address .= $this->getPostOffice().' ';
        $address .= $this->getPostCode().' ';
        $address .= $this->getUpazila().' ';
        $address .= $this->getDistrict().' ';
        return $address;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        $parts = explode(' ', $this->getName());
        if (count($parts) < 2) {
            return '';
        }
        return $parts[count($parts)-1];
    }

    /**
     * @return string
     */
    public function getFirstNames()
    {
        $parts = explode(' ', $this->getName());
        if (count($parts) > 1) {
            unset($parts[count($parts)-1]);
            return implode(' ', $parts);
        }
        return $this->getName();
    }

    public function __construct()
    {
        parent::__construct();
        $this->receivedFunds = new  ArrayCollection();
    }

    /**
     * Set deceased
     *
     * @param boolean $deceased
     *
     * @return ExServiceman
     */
    public function setDeceased($deceased)
    {
        $this->deceased = $deceased;

        return $this;
    }

    /**
     * Get deceased
     *
     * @return bool
     */
    public function isDeceased()
    {
        return $this->deceased;
    }

    /**
     * Set deceasedDate
     *
     * @param \DateTime $deceasedDate
     *
     * @return ExServiceman
     */
    public function setDeceasedDate($deceasedDate)
    {
        $this->deceasedDate = $deceasedDate;

        return $this;
    }

    /**
     * Get deceasedDate
     *
     * @return \DateTime
     */
    public function getDeceasedDate()
    {
        return $this->deceasedDate;
    }

    /**
     * Get disabled
     *
     * @return bool
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * Set disabled
     *
     * @param boolean $disabled
     *
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }

    /**
     * @return string
     */
    public function getDisabilityReason()
    {
        return $this->disabilityReason;
    }

    /**
     * @param string $disabilityReason
     */
    public function setDisabilityReason($disabilityReason)
    {
        $this->disabilityReason = $disabilityReason;
    }

    /**
     * @return boolean
     */
    public function isFreedomFighter()
    {
        return $this->freedomFighter;
    }

    /**
     * @param boolean $freedomFighter
     */
    public function setFreedomFighter($freedomFighter)
    {
        $this->freedomFighter = $freedomFighter;
    }

    /**
     * Set tsNumber
     *
     * @param string $tsNumber
     *
     * @return ExServiceman
     */
    public function setTsNumber($tsNumber)
    {
        $this->tsNumber = $tsNumber;

        return $this;
    }

    /**
     * Get tsNumber
     *
     * @return string
     */
    public function getTsNumber()
    {
        return $this->tsNumber;
    }

    /**
     * Set pensionRate
     *
     * @param float $pensionRate
     *
     * @return ExServiceman
     */
    public function setPensionRate($pensionRate)
    {
        $this->pensionRate = $pensionRate;

        return $this;
    }

    /**
     * Get pensionRate
     *
     * @return float
     */
    public function getPensionRate()
    {
        return $this->pensionRate;
    }

    /**
     * Set retirementDate
     *
     * @param \DateTime $retirementDate
     *
     * @return ExServiceman
     */
    public function setRetirementDate($retirementDate)
    {
        $this->retirementDate = $retirementDate;

        return $this;
    }

    /**
     * Get retirementDate
     *
     * @return \DateTime
     */
    public function getRetirementDate()
    {
        return $this->retirementDate;
    }

    /**
     * Set retirementReason
     *
     * @param RetirementReason $retirementReason
     *
     * @return ExServiceman
     */
    public function setRetirementReason($retirementReason)
    {
        $this->retirementReason = $retirementReason;

        return $this;
    }

    /**
     * Get retirementReason
     *
     * @return RetirementReason
     */
    public function getRetirementReason()
    {
        return $this->retirementReason;
    }

    /**
     * @return ArrayCollection
     */
    public function getReceivedFunds()
    {
        return $this->receivedFunds;
    }


    /**
     * @param mixed $fund
     *
     * @return ExServiceman
     */
    public function addReceivedFund(FundReceived $fund)
    {
        if (!$this->receivedFunds->contains($fund)) {
            $fund->setServiceman($this);
            $this->receivedFunds->add($fund);
        }

        return $this;
    }

    /**
     * @param mixed $fund
     *
     * @return ExServiceman
     */
    public function removeReceivedFund(FundReceived $fund)
    {
        if ($this->receivedFunds->contains($fund)) {
            $this->receivedFunds->removeElement($fund);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTrade()
    {
        return $this->trade;
    }

    /**
     * @param string $trade
     */
    public function setTrade($trade)
    {
        $this->trade = $trade;
    }

    /**
     * @return array
     */
    public function getDisciplineStatus()
    {
        return $this->disciplineStatus;
    }

    /**
     * @param array $disciplineStatus
     */
    public function setDisciplineStatus($disciplineStatus)
    {
        $this->disciplineStatus = $disciplineStatus;
    }

    /**
     * @return \DateTime
     */
    public function getReservistLastDate()
    {
        return $this->reservistLastDate;
    }

    /**
     * @param \DateTime $reservistLastDate
     */
    public function setReservistLastDate($reservistLastDate)
    {
        $this->reservistLastDate = $reservistLastDate;
    }

    /**
     * @return string
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * @param string $remarks
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
    }

    /**
     * Set soldierNumber
     *
     * @param string $soldierNumber
     *
     * @return $this
     */
    public function setSoldierNumber($soldierNumber)
    {
        $this->setIdentityNumber($soldierNumber);

        return $this;
    }

    /**
     * Get soldierNumber
     *
     * @return string
     */
    public function getSoldierNumber()
    {
        return $this->getIdentityNumber();
    }

    /**
     * @return string
     */
    public function getDeceasedPlace()
    {
        return $this->deceasedPlace;
    }

    /**
     * @param string $deceasedPlace
     */
    public function setDeceasedPlace($deceasedPlace)
    {
        $this->deceasedPlace = $deceasedPlace;
    }

    /**
     * @return string
     */
    public function getDeceasedReason()
    {
        return $this->deceasedReason;
    }

    /**
     * @param string $deceasedReason
     */
    public function setDeceasedReason($deceasedReason)
    {
        $this->deceasedReason = $deceasedReason;
    }

    /**
     * @return boolean
     */
    public function isUnMission()
    {
        return $this->unMission;
    }

    /**
     * @param boolean $unMission
     */
    public function setUnMission($unMission)
    {
        $this->unMission = $unMission;
    }

    /**
     * @return string
     */
    public function getSpouse()
    {
        $members = $this->families;
        foreach ($members as $member) {
            if (strtolower($member->getRelationType()) == 'spouse') {
                return $member;
            }
        }
        return null;
    }

    /**
     * @return boolean
     */
    public function isArmyCommandoCourse()
    {
        return $this->armyCommandoCourse;
    }

    /**
     * @param boolean $armyCommandoCourse
     */
    public function setArmyCommandoCourse($armyCommandoCourse)
    {
        $this->armyCommandoCourse = $armyCommandoCourse;
    }

    /**
     * @return boolean
     */
    public function isSniperCourse()
    {
        return $this->sniperCourse;
    }

    /**
     * @param boolean $sniperCourse
     */
    public function setSniperCourse($sniperCourse)
    {
        $this->sniperCourse = $sniperCourse;
    }

    /**
     * @return boolean
     */
    public function isExplosionCourse()
    {
        return $this->explosionCourse;
    }

    /**
     * @param boolean $explosionCourse
     */
    public function setExplosionCourse($explosionCourse)
    {
        $this->explosionCourse = $explosionCourse;
    }

    /**
     * @return boolean
     */
    public function isEmanationTechnician()
    {
        return $this->emanationTechnician;
    }

    /**
     * @param boolean $emanationTechnician
     */
    public function setEmanationTechnician($emanationTechnician)
    {
        $this->emanationTechnician = $emanationTechnician;
    }

    /**
     * @return boolean
     */
    public function isBmaOrSIAndT()
    {
        return $this->bmaOrSIAndT;
    }

    /**
     * @param boolean $bmaOrSIAndT
     */
    public function setBmaOrSIAndT($bmaOrSIAndT)
    {
        $this->bmaOrSIAndT = $bmaOrSIAndT;
    }

    /**
     * @return string
     */
    public function getEmergencyName()
    {
        return $this->emergencyName;
    }

    /**
     * @param string $emergencyName
     */
    public function setEmergencyName($emergencyName)
    {
        $this->emergencyName = $emergencyName;
    }

    /**
     * @return string
     */
    public function getEmergencyRelation()
    {
        return $this->emergencyRelation;
    }

    /**
     * @param string $emergencyRelation
     */
    public function setEmergencyRelation($emergencyRelation)
    {
        $this->emergencyRelation = $emergencyRelation;
    }

    /**
     * @return string
     */
    public function getEmergencyNumber()
    {
        return $this->emergencyNumber;
    }

    /**
     * @param string $emergencyNumber
     */
    public function setEmergencyNumber($emergencyNumber)
    {
        $this->emergencyNumber = $emergencyNumber;
    }

    /**
     * @return string
     */
    public function getEmergencyAddress()
    {
        return $this->emergencyAddress;
    }

    /**
     * @param string $emergencyAddress
     */
    public function setEmergencyAddress($emergencyAddress)
    {
        $this->emergencyAddress = $emergencyAddress;
    }

    /**
     * @return string
     */
    public function getInheritName()
    {
        return $this->inheritName;
    }

    /**
     * @param string $inheritName
     */
    public function setInheritName($inheritName)
    {
        $this->inheritName = $inheritName;
    }

    /**
     * @return string
     */
    public function getInheritRelation()
    {
        return $this->inheritRelation;
    }

    /**
     * @param string $inheritRelation
     */
    public function setInheritRelation($inheritRelation)
    {
        $this->inheritRelation = $inheritRelation;
    }

    /**
     * @return string
     */
    public function getReEmployment()
    {
        return $this->reEmployment;
    }

    /**
     * @param string $reEmployment
     */
    public function setReEmployment($reEmployment)
    {
        $this->reEmployment = $reEmployment;
    }

    /**
     * @return boolean
     */
    public function isShantiNebas()
    {
        return $this->shantiNebas;
    }

    /**
     * @param boolean $shantiNebas
     */
    public function setShantiNebas($shantiNebas)
    {
        $this->shantiNebas = $shantiNebas;
    }

    /**
     * @return boolean
     */
    public function isAfterRetirementCityOrVillage()
    {
        return $this->afterRetirementCityOrVillage;
    }

    /**
     * @param boolean $afterRetirementCityOrVillage
     */
    public function setAfterRetirementCityOrVillage($afterRetirementCityOrVillage)
    {
        $this->afterRetirementCityOrVillage = $afterRetirementCityOrVillage;
    }

    /**
     * @return string
     */
    public function getAfterRetirementLivingNature()
    {
        return $this->afterRetirementLivingNature;
    }

    /**
     * @param string $afterRetirementLivingNature
     */
    public function setAfterRetirementLivingNature($afterRetirementLivingNature)
    {
        $this->afterRetirementLivingNature = $afterRetirementLivingNature;
    }

    /**
     * @return string
     */
    public function getAfterRetirementPlantingLand()
    {
        return $this->afterRetirementPlantingLand;
    }

    /**
     * @param string $afterRetirementPlantingLand
     */
    public function setAfterRetirementPlantingLand($afterRetirementPlantingLand)
    {
        $this->afterRetirementPlantingLand = $afterRetirementPlantingLand;
    }

    /**
     * @return string
     */
    public function getAfterRetirementSourceOfIncome()
    {
        return $this->afterRetirementSourceOfIncome;
    }

    /**
     * @param string $afterRetirementSourceOfIncome
     */
    public function setAfterRetirementSourceOfIncome($afterRetirementSourceOfIncome)
    {
        $this->afterRetirementSourceOfIncome = $afterRetirementSourceOfIncome;
    }

    /**
     * @return string
     */
    public function getInheritNID()
    {
        return $this->inheritNID;
    }

    /**
     * @param string $inheritNID
     */
    public function setInheritNID($inheritNID)
    {
        $this->inheritNID = $inheritNID;
    }

    /**
     * @return \DateTime
     */
    public function getInheritBirthDate()
    {
        return $this->inheritBirthDate;
    }

    /**
     * @param \DateTime $inheritBirthDate
     */
    public function setInheritBirthDate($inheritBirthDate)
    {
        $this->inheritBirthDate = $inheritBirthDate;
    }

    /**
     * @return Dispensary
     */
    public function getDispensary()
    {
        return $this->dispensary;
    }

    /**
     * @param Dispensary $dispensary
     */
    public function setDispensary($dispensary)
    {
        $this->dispensary = $dispensary;
    }

    /**
     * @return mixed
     */
    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * @param mixed $additionalInfo
     */
    public function setAdditionalInfo($additionalInfo)
    {
        $additionalInfo->setServiceman($this);
        $this->additionalInfo = $additionalInfo;
    }
}

