<?php

namespace BudgetBundle\Entity;

use AppBundle\Entity\FinancialYear;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PreBudgetSummary
 *
 * @ORM\Table(name="budget_pre_budget_summary")
 * @ORM\Entity(repositoryClass="BudgetBundle\Repository\PreBudgetSummaryRepository")
 */
class PreBudgetSummary extends BaseWorkflowEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FinancialYear")
     * @ORM\JoinColumn(name="financial_year_id", referencedColumnName="id")
     */
    private $financialYear;

    /**
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="BudgetBundle\Entity\PreBudgetSummaryDetail", mappedBy="budgetSummary")
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
    public function getPreBudgetSummaryDetails()
    {
        return $this->budgetSummaryDetails;
    }

    /**
     * @param mixed $preBudgetSummaryDetails
     *
     * @return PreBudgetSummary
     */
    public function setPreBudgetSummaryDetails($preBudgetSummaryDetails)
    {
        $this->budgetSummaryDetails = $preBudgetSummaryDetails;

        return $this;
    }

    public function addSummaryBudgetDetails(PreBudgetSummary $budget)
    {
        if (!$this->budgetSummaryDetails->contains($budget)) {
            $this->budgetSummaryDetails->add($budget);
        }
    }

    public function removeSummaryBudgetDetails(PreBudgetSummary $budget)
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
     * @return PreBudgetSummary
     */
    public function setStatus($status)
    {
        $this->status = $status;

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

