<?php

namespace BudgetBundle\Entity;

use AppBundle\Entity\FinancialYear;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * BudgetIncomeSummary
 *
 * @ORM\Table(name="budget_income_summary")
 * @ORM\Entity(repositoryClass="BudgetBundle\Repository\BudgetIncomeSummaryRepository")
 */
class BudgetIncomeSummary extends BaseWorkflowEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FinancialYear")
     * @ORM\JoinColumn(name="financial_year_id", referencedColumnName="id")
     */
    private $financialYear;

    /**
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status = 'draft';

    /**
     * @ORM\Column(name="budget_status", type="string", length=255)
     */
    private $budgetStatus = 'draft';

    /**
     * @ORM\Column(name="amendment_status", type="string", length=255)
     */
    private $amendmentStatus = 'draft';

    /**
     * @ORM\Column(name="amendment_started", type="boolean")
     */
    private $amendmentStarted = false;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="BudgetBundle\Entity\BudgetIncomeSummaryDetail", mappedBy="budgetSummary")
     */
    private $budgetSummaryDetails;

    public function __construct()
    {
        $this->budgetSummaryDetails = new ArrayCollection();
    }

    /**
     * @return FinancialYear
     */
    public function getFinancialYear()
    {
        return $this->financialYear;
    }

    /**
     * @param mixed $financialYear
     *
     * @return $this
     */
    public function setFinancialYear(FinancialYear $financialYear)
    {
        $this->financialYear = $financialYear;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getBudgetSummaryDetails()
    {
        return $this->budgetSummaryDetails;
    }

    /**
     * @param mixed $budgetSummaryDetails
     *
     * @return $this
     */
    public function setBudgetSummaryDetails($budgetSummaryDetails)
    {
        $this->budgetSummaryDetails = $budgetSummaryDetails;

        return $this;
    }

    public function addSummaryBudgetDetails(BudgetSummary $budget)
    {
        if (!$this->budgetSummaryDetails->contains($budget)) {
            $this->budgetSummaryDetails->add($budget);
        }
    }

    public function removeSummaryBudgetDetails(BudgetSummary $budget)
    {
        if ($this->budgetSummaryDetails->contains($budget)) {
            $this->budgetSummaryDetails->removeElement($budget);
        }
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getAmendmentStarted()
    {
        return $this->amendmentStarted;
    }

    /**
     * @return boolean
     */
    public function isAmendmentStarted()
    {
        return $this->amendmentStarted;
    }

    /**
     * @param mixed $amendmentStarted
     *
     * @return $this
     */
    public function setAmendmentStarted($amendmentStarted)
    {
        $this->amendmentStarted = $amendmentStarted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmendmentStatus()
    {
        return $this->amendmentStatus;
    }

    /**
     * @param mixed $amendmentStatus
     *
     * @return $this
     */
    public function setAmendmentStatus($amendmentStatus)
    {
        $this->amendmentStatus = $amendmentStatus;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBudgetStatus()
    {
        return $this->budgetStatus;
    }

    /**
     * @param mixed $budgetStatus
     *
     * @return $this
     */
    public function setBudgetStatus($budgetStatus)
    {
        $this->budgetStatus = $budgetStatus;

        return $this;
    }

    public function getAmendmentRemarks()
    {
        $data = [];
        foreach ($this->getStepRemarks() as $remark) {
            if (isset($remark['place']) && strpos($remark['place'], 'amendment') === 0) {
                $data[] = $remark;
            }
        }

        return $data;
    }
}

