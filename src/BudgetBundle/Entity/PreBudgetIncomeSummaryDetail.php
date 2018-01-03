<?php

namespace BudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PreBudgetSummaryDetails
 *
 * @ORM\Table(name="budget_pre_budget_income_summary_details")
 * @ORM\Entity(repositoryClass="BudgetBundle\Repository\PreBudgetIncomeSummaryDetailRepository")
 */
class PreBudgetIncomeSummaryDetail
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="BudgetBundle\Entity\PreBudgetIncomeSummary", inversedBy="budgetSummaryDetails")
     * @ORM\JoinColumn(name="pre_budget_income_summary_id", referencedColumnName="id")
     */
    private $budgetSummary;

    /**
     * @ORM\ManyToOne(targetEntity="BudgetBundle\Entity\BudgetIncomeHead")
     * @ORM\JoinColumn(name="budget_income_head_id", referencedColumnName="id")
     */
    private $budgetHead;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", nullable=true)
     */
    private $amount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="request_amount", type="float")
     */
    private $requestAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="next_year_projection", type="float")
     */
    private $nextYearProjectionAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="after_next_year_projection", type="float")
     */
    private $afterNextYearProjectionAmount = 0;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set budgetSummary
     *
     * @param PreBudgetSummary $budgetSummary
     *
     * @return $this
     */
    public function setBudgetSummary($budgetSummary)
    {
        $this->budgetSummary = $budgetSummary;

        return $this;
    }

    /**
     * Get budgetSummary
     *
     * @return PreBudgetIncomeSummary
     */
    public function getBudgetSummary()
    {
        return $this->budgetSummary;
    }

    /**
     * Set budgetHead
     *
     * @param \stdClass $budgetHead
     *
     * @return $this
     */
    public function setBudgetHead($budgetHead)
    {
        $this->budgetHead = $budgetHead;

        return $this;
    }

    /**
     * Get budgetHead
     *
     * @return BudgetHead
     */
    public function getBudgetHead()
    {
        return $this->budgetHead;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set requestAmount
     *
     * @param float $requestAmount
     *
     * @return $this
     */
    public function setRequestAmount($requestAmount)
    {
        $this->requestAmount = $requestAmount;

        return $this;
    }

    /**
     * Get requestAmount
     *
     * @return float
     */
    public function getRequestAmount()
    {
        return $this->requestAmount;
    }

    /**
     * @return float
     */
    public function getNextYearProjectionAmount()
    {
        return $this->nextYearProjectionAmount;
    }

    /**
     * @param float $nextYearProjectionAmount
     *
     * @return $this
     */
    public function setNextYearProjectionAmount($nextYearProjectionAmount)
    {
        $this->nextYearProjectionAmount = $nextYearProjectionAmount;

        return $this;
    }

    /**
     * @return float
     */
    public function getAfterNextYearProjectionAmount()
    {
        return $this->afterNextYearProjectionAmount;
    }

    /**
     * @param float $afterNextYearProjectionAmount
     *
     * @return $this
     */
    public function setAfterNextYearProjectionAmount($afterNextYearProjectionAmount)
    {
        $this->afterNextYearProjectionAmount = $afterNextYearProjectionAmount;

        return $this;
    }

}

