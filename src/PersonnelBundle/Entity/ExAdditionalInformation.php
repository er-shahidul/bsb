<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExAdditionalInformation
 * @ORM\Table(name="ex_additional_information")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\ExAdditionalInformationRepository")
 */
class ExAdditionalInformation extends BaseAdditionalInformation
{
    /**
     * @var ExServiceman
     *
     * @ORM\OneToOne(targetEntity="PersonnelBundle\Entity\ExServiceman", inversedBy="additionalInfo")
     * @ORM\JoinColumn(name="serviceman_id", referencedColumnName="id")
     */
    protected $serviceman;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\District")
     */
    private $inheritPermanentDistrict;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\Upazila")
     */
    private $inheritPermanentUpazila;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="inherit_permanent_post_office", type="string", length=255, nullable=true)
     */
    private $inheritPermanentPostOffice;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="inherit_permanent_post_code", type="string", length=255, nullable=true)
     */
    private $inheritPermanentPostCode;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="inherit_permanent_village", type="string", length=255, nullable=true)
     */
    private $inheritPermanentVillage;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\District")
     */
    private $inheritDistrict;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\Upazila")
     */
    private $inheritUpazila;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="inherit_post_office", type="string", length=255, nullable=true)
     */
    private $inheritPostOffice;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="inherit_post_code", type="string", length=255, nullable=true)
     */
    private $inheritPostCode;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="inherit_village", type="string", length=255, nullable=true)
     */
    private $inheritVillage;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="inherit_guardian", type="string", length=255, nullable=true)
     */
    private $inheritGuardian;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="inherit_occupation", type="string", length=255, nullable=true)
     */
    private $inheritOccupation;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="children_info", type="string", length=1024, nullable=true)
     */
    private $childrenInfo;

    /**
     * @var bool
     *
     * @ORM\Column(name="fixed_or_current_asset", type="boolean", nullable=true)
     */
    private $fixedOrCurrentAsset = false;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="asset_info", type="string", length=1024, nullable=true)
     */
    private $assetInfo;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="amount_of_land", type="string", length=1024, nullable=true)
     */
    private $amountOfLand;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="total_income", type="string", length=1024, nullable=true)
     */
    private $totalIncome;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="monthly_income", type="string", length=1024, nullable=true)
     */
    private $monthlyIncome;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="pension_info", type="string", length=1024, nullable=true)
     */
    private $pensionInfo;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="loan_info", type="string", length=1024, nullable=true)
     */
    private $loanInfo;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="commutation_info", type="string", length=1024, nullable=true)
     */
    private $commutationInfo;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="mission_income", type="string", length=1024, nullable=true)
     */
    private $missionIncome;

    /**
     * @return bool
     */
    public function hasFixedOrCurrentAsset()
    {
        return $this->fixedOrCurrentAsset;
    }

    /**
     * @param bool $fixedOrCurrentAsset
     */
    public function setFixedOrCurrentAsset($fixedOrCurrentAsset)
    {
        $this->fixedOrCurrentAsset = $fixedOrCurrentAsset;
    }

    /**
     * @return mixed
     */
    public function getInheritPermanentDistrict()
    {
        return $this->inheritPermanentDistrict;
    }

    /**
     * @param mixed $inheritPermanentDistrict
     */
    public function setInheritPermanentDistrict($inheritPermanentDistrict)
    {
        $this->inheritPermanentDistrict = $inheritPermanentDistrict;
    }

    /**
     * @return mixed
     */
    public function getInheritPermanentUpazila()
    {
        return $this->inheritPermanentUpazila;
    }

    /**
     * @param mixed $inheritPermanentUpazila
     */
    public function setInheritPermanentUpazila($inheritPermanentUpazila)
    {
        $this->inheritPermanentUpazila = $inheritPermanentUpazila;
    }

    /**
     * @return string
     */
    public function getInheritPermanentPostOffice()
    {
        return $this->inheritPermanentPostOffice;
    }

    /**
     * @param string $inheritPermanentPostOffice
     */
    public function setInheritPermanentPostOffice($inheritPermanentPostOffice)
    {
        $this->inheritPermanentPostOffice = $inheritPermanentPostOffice;
    }

    /**
     * @return string
     */
    public function getInheritPermanentPostCode()
    {
        return $this->inheritPermanentPostCode;
    }

    /**
     * @param string $inheritPermanentPostCode
     */
    public function setInheritPermanentPostCode($inheritPermanentPostCode)
    {
        $this->inheritPermanentPostCode = $inheritPermanentPostCode;
    }

    /**
     * @return string
     */
    public function getInheritPermanentVillage()
    {
        return $this->inheritPermanentVillage;
    }

    /**
     * @param string $inheritPermanentVillage
     */
    public function setInheritPermanentVillage($inheritPermanentVillage)
    {
        $this->inheritPermanentVillage = $inheritPermanentVillage;
    }

    /**
     * @return mixed
     */
    public function getInheritDistrict()
    {
        return $this->inheritDistrict;
    }

    /**
     * @param mixed $inheritDistrict
     */
    public function setInheritDistrict($inheritDistrict)
    {
        $this->inheritDistrict = $inheritDistrict;
    }

    /**
     * @return mixed
     */
    public function getInheritUpazila()
    {
        return $this->inheritUpazila;
    }

    /**
     * @param mixed $inheritUpazila
     */
    public function setInheritUpazila($inheritUpazila)
    {
        $this->inheritUpazila = $inheritUpazila;
    }

    /**
     * @return string
     */
    public function getInheritPostOffice()
    {
        return $this->inheritPostOffice;
    }

    /**
     * @param string $inheritPostOffice
     */
    public function setInheritPostOffice($inheritPostOffice)
    {
        $this->inheritPostOffice = $inheritPostOffice;
    }

    /**
     * @return string
     */
    public function getInheritPostCode()
    {
        return $this->inheritPostCode;
    }

    /**
     * @param string $inheritPostCode
     */
    public function setInheritPostCode($inheritPostCode)
    {
        $this->inheritPostCode = $inheritPostCode;
    }

    /**
     * @return string
     */
    public function getInheritVillage()
    {
        return $this->inheritVillage;
    }

    /**
     * @param string $inheritVillage
     */
    public function setInheritVillage($inheritVillage)
    {
        $this->inheritVillage = $inheritVillage;
    }

    /**
     * @return string
     */
    public function getInheritGuardian()
    {
        return $this->inheritGuardian;
    }

    /**
     * @param string $inheritGuardian
     */
    public function setInheritGuardian($inheritGuardian)
    {
        $this->inheritGuardian = $inheritGuardian;
    }

    /**
     * @return string
     */
    public function getInheritOccupation()
    {
        return $this->inheritOccupation;
    }

    /**
     * @param string $inheritOccupation
     */
    public function setInheritOccupation($inheritOccupation)
    {
        $this->inheritOccupation = $inheritOccupation;
    }

    /**
     * @return string
     */
    public function getChildrenInfo()
    {
        return $this->childrenInfo;
    }

    /**
     * @param string $childrenInfo
     */
    public function setChildrenInfo($childrenInfo)
    {
        $this->childrenInfo = $childrenInfo;
    }

    /**
     * @return string
     */
    public function getAssetInfo()
    {
        return $this->assetInfo;
    }

    /**
     * @param string $assetInfo
     */
    public function setAssetInfo($assetInfo)
    {
        $this->assetInfo = $assetInfo;
    }

    /**
     * @return string
     */
    public function getAmountOfLand()
    {
        return $this->amountOfLand;
    }

    /**
     * @param string $amountOfLand
     */
    public function setAmountOfLand($amountOfLand)
    {
        $this->amountOfLand = $amountOfLand;
    }

    /**
     * @return string
     */
    public function getTotalIncome()
    {
        return $this->totalIncome;
    }

    /**
     * @param string $totalIncome
     */
    public function setTotalIncome($totalIncome)
    {
        $this->totalIncome = $totalIncome;
    }

    /**
     * @return string
     */
    public function getMonthlyIncome()
    {
        return $this->monthlyIncome;
    }

    /**
     * @param string $monthlyIncome
     */
    public function setMonthlyIncome($monthlyIncome)
    {
        $this->monthlyIncome = $monthlyIncome;
    }

    /**
     * @return string
     */
    public function getPensionInfo()
    {
        return $this->pensionInfo;
    }

    /**
     * @param string $pensionInfo
     */
    public function setPensionInfo($pensionInfo)
    {
        $this->pensionInfo = $pensionInfo;
    }

    /**
     * @return string
     */
    public function getLoanInfo()
    {
        return $this->loanInfo;
    }

    /**
     * @param string $loanInfo
     */
    public function setLoanInfo($loanInfo)
    {
        $this->loanInfo = $loanInfo;
    }

    /**
     * @return string
     */
    public function getCommutationInfo()
    {
        return $this->commutationInfo;
    }

    /**
     * @param string $commutationInfo
     */
    public function setCommutationInfo($commutationInfo)
    {
        $this->commutationInfo = $commutationInfo;
    }

    /**
     * @return string
     */
    public function getMissionIncome()
    {
        return $this->missionIncome;
    }

    /**
     * @param string $missionIncome
     */
    public function setMissionIncome($missionIncome)
    {
        $this->missionIncome = $missionIncome;
    }
}