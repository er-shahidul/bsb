<?php

namespace BudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BudgetSummaryDetails
 *
 * @ORM\Table(name="budget_summary_details")
 * @ORM\Entity(repositoryClass="BudgetBundle\Repository\BudgetSummaryDetailRepository")
 */
class BudgetSummaryDetail
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
     * @ORM\ManyToOne(targetEntity="BudgetBundle\Entity\BudgetSummary", inversedBy="budgetSummaryDetails")
     * @ORM\JoinColumn(name="budget_summary_id", referencedColumnName="id")
     */
    private $budgetSummary;

    /**
     * @ORM\ManyToOne(targetEntity="BudgetBundle\Entity\BudgetHead")
     * @ORM\JoinColumn(name="budget_head_id", referencedColumnName="id")
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
     * @ORM\Column(name="remaining_amount", type="float")
     */
    private $remainingAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="request_amount", type="float")
     */
    private $requestAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="sanction_amount", type="float")
     */
    private $sanctionAmount = 0;

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
     * @var float
     *
     * @ORM\Column(name="amendment_request_amount", type="float")
     */
    private $amendmentRequestAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="amendment_sanction_amount", type="float")
     */
    private $amendmentSanctionAmount = 0;


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
     * @param BudgetSummary $budgetSummary
     *
     * @return BudgetSummaryDetail
     */
    public function setBudgetSummary($budgetSummary)
    {
        $this->budgetSummary = $budgetSummary;

        return $this;
    }

    /**
     * Get budgetSummary
     *
     * @return \stdClass
     */
    public function getBudgetSummary()
    {
        return $this->budgetSummary;
    }

    /**
     * Set budgetHead
     *
     * @param BudgetHead $budgetHead
     *
     * @return BudgetSummaryDetail
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
     * @return BudgetSummaryDetail
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
     * @return BudgetSummaryDetail
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
     * @return BudgetSummaryDetail
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
     * @return BudgetSummaryDetail
     */
    public function setAfterNextYearProjectionAmount($afterNextYearProjectionAmount)
    {
        $this->afterNextYearProjectionAmount = $afterNextYearProjectionAmount;

        return $this;
    }

    /**
     * @return float
     */
    public function getRemainingAmount()
    {
        return $this->remainingAmount;
    }

    /**
     * @param float $remainingAmount
     *
     * @return BudgetSummaryDetail
     */
    public function setRemainingAmount($remainingAmount)
    {
        $this->remainingAmount = $remainingAmount;

        return $this;
    }

    /**
     * @return float
     */
    public function getSanctionAmount()
    {
        return $this->sanctionAmount;
    }

    /**
     * @param float $sanctionAmount
     *
     * @return BudgetSummaryDetail
     */
    public function setSanctionAmount($sanctionAmount)
    {
        $this->sanctionAmount = $sanctionAmount;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmendmentRequestAmount()
    {
        return $this->amendmentRequestAmount;
    }

    /**
     * @param float $amendmentRequestAmount
     *
     * @return BudgetSummaryDetail
     */
    public function setAmendmentRequestAmount($amendmentRequestAmount)
    {
        $this->amendmentRequestAmount = $amendmentRequestAmount;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmendmentSanctionAmount()
    {
        return $this->amendmentSanctionAmount;
    }

    /**
     * @param float $amendmentSanctionAmount
     *
     * @return BudgetSummaryDetail
     */
    public function setAmendmentSanctionAmount($amendmentSanctionAmount)
    {
        $this->amendmentSanctionAmount = $amendmentSanctionAmount;

        return $this;
    }

    public function getDistributedAmount()
    {
        return $this->getAmount() - $this->getRemainingAmount();
    }
}

