<?php

namespace WelfareBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="micro_credit_application_detail")
 */
class MicroCreditApplicationDetail
{
    /**
     * @ORM\OneToOne(targetEntity="WelfareBundle\Entity\MicroCreditApplication", inversedBy="microCreditDetail")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     * })
     */
    protected $application;

    /**
     * @ORM\ManyToOne(targetEntity="WelfareBundle\Entity\MicroCreditProjectType")
     * @ORM\JoinColumn(name="project_type")
     * @ORM\OrderBy({"sort" = "asc"})
     */
    private $projectType;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="project_name", type="string", length=255)
     */
    private $projectName;

    /**
     * @var integer
     *
     * @ORM\Column(name="requestAmount", type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "Not a valid amount",
     * )
     */
    private $requestAmount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="installment_amount", type="integer")
     */
    private $installmentAmount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="last_installment_amount", type="integer")
     */
    private $lastInstallmentAmount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="installment_free_months", type="integer")
     */
    private $installmentFreeMonths = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_of_installments", type="integer")
     */
    private $noOfInstallments = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_paid", type="integer")
     */
    private $totalPaid = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="reassessment_count", type="smallint")
     */
    private $reassessmentCount = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="loan_completed", type="boolean")
     */
    private $loanCompleted = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="installment_start_date", type="date", nullable=true)
     */
    private $installmentStartDate;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getProjectType()
    {
        return $this->projectType;
    }

    /**
     * @param mixed $projectType
     */
    public function setProjectType($projectType)
    {
        $this->projectType = $projectType;
    }

    /**
     * @return string
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * @param string $projectName
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;
    }

    /**
     * @return integer
     */
    public function getRequestAmount()
    {
        return $this->requestAmount;
    }

    /**
     * @param integer $requestAmount
     */
    public function setRequestAmount($requestAmount)
    {
        $this->requestAmount = $requestAmount;
    }

    /**
     * @return mixed
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param mixed $application
     */
    public function setApplication($application)
    {
        $this->application = $application;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return integer
     */
    public function getInstallmentAmount()
    {
        return $this->installmentAmount;
    }

    /**
     * @param integer $installmentAmount
     */
    public function setInstallmentAmount($installmentAmount)
    {
        $this->installmentAmount = $installmentAmount;
    }

    /**
     * @return int
     */
    public function getInstallmentFreeMonths()
    {
        return $this->installmentFreeMonths;
    }

    /**
     * @param int $installmentFreeMonths
     */
    public function setInstallmentFreeMonths($installmentFreeMonths)
    {
        $this->installmentFreeMonths = $installmentFreeMonths;
    }

    /**
     * @return int
     */
    public function getTotalPaid()
    {
        return $this->totalPaid;
    }

    /**
     * @param int $totalPaid
     */
    public function setTotalPaid($totalPaid)
    {
        $this->totalPaid = $totalPaid;
    }

    /**
     * @return bool
     */
    public function isLoanCompleted()
    {
        return $this->loanCompleted;
    }

    /**
     * @param bool $loanCompleted
     */
    public function setLoanCompleted($loanCompleted)
    {
        $this->loanCompleted = $loanCompleted;
    }

    /**
     * @return int
     */
    public function getNoOfInstallments()
    {
        return $this->noOfInstallments;
    }

    /**
     * @param int $noOfInstallments
     */
    public function setNoOfInstallments($noOfInstallments)
    {
        $this->noOfInstallments = $noOfInstallments;
    }

    /**
     * @return \DateTime
     */
    public function getInstallmentStartDate()
    {
        return $this->installmentStartDate;
    }

    /**
     * @param \DateTime $installmentStartDate
     */
    public function setInstallmentStartDate($installmentStartDate)
    {
        $this->installmentStartDate = $installmentStartDate;
    }

    /**
     * @return int
     */
    public function getLastInstallmentAmount()
    {
        return $this->lastInstallmentAmount;
    }

    /**
     * @param int $lastInstallmentAmount
     */
    public function setLastInstallmentAmount($lastInstallmentAmount)
    {
        $this->lastInstallmentAmount = $lastInstallmentAmount;
    }

    /**
     * @return int
     */
    public function getReassessmentCount()
    {
        return $this->reassessmentCount;
    }

    /**
     * @param int $reassessmentCount
     */
    public function setReassessmentCount($reassessmentCount)
    {
        $this->reassessmentCount = $reassessmentCount;
    }
}
