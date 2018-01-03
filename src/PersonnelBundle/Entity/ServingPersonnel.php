<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ServingPersonnel
 * @ORM\Table(name="serving_personnel")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"base" = "ServingPersonnel", "military" = "ServingMilitary", "civilian" = "ServingCivilian"})
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\ServingPersonnelRepository")
 * @UniqueEntity("identityNumber")
 */
class ServingPersonnel extends Personnel
{
    /**
     * @var bool
     *
     * @ORM\Column(name="un_mission", type="boolean")
     */
    private $unMission = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_retirement", type="date", nullable=true)
     */
    private $dateOfRetirement;

    /**
     * @var string
     *
     * @ORM\Column(name="trade", type="string", length=255, nullable=true)
     */
    private $trade;

    /**
     * @var string
     *
     * @ORM\Column(name="medical_classification", type="string", length=255, nullable=true)
     */
    private $medicalClassification;

    /**
     * @var string
     *
     * @ORM\Column(name="discipline_information", type="string", length=255, nullable=true)
     */
    private $disciplineInformation;

    /**
     * @var string
     *
     * @ORM\Column(name="service_condition", type="string", length=255, nullable=true)
     */
    private $serviceCondition;

    /**
     * @var string
     *
     * @ORM\Column(name="present_rank", type="string", length=255, nullable=true)
     */
    private $presentRank;

    /**
     * @var array
     *
     * @ORM\Column(name="apr_grading", type="array", nullable=true)
     */
    private $aprGrading;

    /**
     * @var string
     *
     * @ORM\Column(name="remarks", type="string", length=512, nullable=true)
     */
    private $remarks;

    /**
     * @var string
     *
     * @ORM\Column(name="discipline_status", type="string", length=255, nullable=true)
     */
    private $disciplineStatus;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\FamilyInformation", mappedBy="serviceman", cascade={"persist"}, orphanRemoval=true )
     */
    protected $families;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\EmploymentInformation", mappedBy="serviceman", cascade={"persist"}, orphanRemoval=true )
     */
    protected $employmentInformations;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\CareerInformation", mappedBy="serviceman", cascade={"persist"}, orphanRemoval=true )
     */
    protected $careers;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\EducationalInformation", mappedBy="serviceman", cascade={"persist"}, orphanRemoval=true )
     */
    protected $educations;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\ServiceInformation", mappedBy="serviceman", cascade={"persist"}, orphanRemoval=true )
     */
    protected $servicesInfo;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\TrainingInformation", mappedBy="serviceman", cascade={"persist"}, orphanRemoval=true )
     */
    protected $trainings;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getDisciplineStatus()
    {
        return $this->disciplineStatus;
    }

    /**
     * @param string $disciplineStatus
     */
    public function setDisciplineStatus($disciplineStatus)
    {
        $this->disciplineStatus = $disciplineStatus;
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
    public function getAprGrading()
    {
        return $this->aprGrading;
    }

    /**
     * @param array $aprGrading
     */
    public function setAprGrading($aprGrading)
    {
        $this->aprGrading = $aprGrading;
    }

    /**
     * @return \DateTime
     */
    public function getDateOfRetirement()
    {
        return $this->dateOfRetirement;
    }

    /**
     * @param \DateTime $dateOfRetirement
     */
    public function setDateOfRetirement($dateOfRetirement)
    {
        $this->dateOfRetirement = $dateOfRetirement;
    }

    /**
     * @return string
     */
    public function getPresentRank()
    {
        return $this->presentRank;
    }

    /**
     * @param string $presentRank
     */
    public function setPresentRank($presentRank)
    {
        $this->presentRank = $presentRank;
    }

    /**
     * @return string
     */
    public function getMedicalClassification()
    {
        return $this->medicalClassification;
    }

    /**
     * @param string $medicalClassification
     */
    public function setMedicalClassification($medicalClassification)
    {
        $this->medicalClassification = $medicalClassification;
    }

    /**
     * @return string
     */
    public function getDisciplineInformation()
    {
        return $this->disciplineInformation;
    }

    /**
     * @param string $disciplineInformation
     */
    public function setDisciplineInformation($disciplineInformation)
    {
        $this->disciplineInformation = $disciplineInformation;
    }

    /**
     * @return string
     */
    public function getServiceCondition()
    {
        return $this->serviceCondition;
    }

    /**
     * @param string $serviceCondition
     */
    public function setServiceCondition($serviceCondition)
    {
        $this->serviceCondition = $serviceCondition;
    }
}