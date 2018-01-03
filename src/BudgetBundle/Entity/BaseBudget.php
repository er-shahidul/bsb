<?php

namespace BudgetBundle\Entity;

use AppBundle\Entity\FinancialYear;
use AppBundle\Entity\OfficeAwareEntityInterface;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Budget
 * @ORM\Table(name="budget")
 * @ORM\Entity(repositoryClass="BudgetBundle\Repository\BaseBudgetRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="budget_type", type="string")
 * @ORM\DiscriminatorMap({"base" = "BaseBudget", "new" = "Budget", "fund-request" = "FundRequest"})
 */
class BaseBudget extends BaseWorkflowEntity implements OfficeAwareEntityInterface
{
    const STATUS_FINAL = 'final';

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FinancialYear")
     * @ORM\JoinColumn(name="financial_year_id", referencedColumnName="id")
     */
    private $financialYear;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status = 'draft';

    /**
     * @ORM\OneToMany(targetEntity="BudgetBundle\Entity\BudgetDetail", mappedBy="budget", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $budgetDetails;

    public function __construct()
    {
        $this->budgetDetails = new ArrayCollection();
    }

    /**
     * Set FinancialYear
     *
     * @param FinancialYear $financialYear
     *
     * @return $this
     */
    public function setFinancialYear(FinancialYear $financialYear)
    {
        $this->financialYear = $financialYear;

        return $this;
    }

    /**
     * Get FinancialYear
     *
     * @return FinancialYear
     */
    public function getFinancialYear()
    {
        return $this->financialYear;
    }

    /**
     * @return mixed
     */
    public function getBudgetDetails()
    {
        return $this->budgetDetails;
    }

    /**
     * @param mixed $budgetDetails
     *
     * @return $this
     */
    public function setBudgetDetails($budgetDetails)
    {
        $this->budgetDetails = $budgetDetails;

        return $this;
    }

    public function addBudgetDetail(BudgetDetail $budget)
    {
        if (!$this->budgetDetails->contains($budget)) {
            $this->budgetDetails->add($budget);
        }
    }

    public function removeBudgetDetail(BudgetDetail $budget)
    {
        if ($this->budgetDetails->contains($budget)) {
            $this->budgetDetails->removeElement($budget);
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
     * @param $amount
     * @return float|int
     */
    static public function encodeAmount($amount)
    {
        return $amount / 1000;
    }

    /**
     * @param $amount
     * @return mixed
     */
    static public function decodeAmount($amount)
    {
        return $amount * 1000;
    }
}

