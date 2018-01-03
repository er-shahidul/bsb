<?php

namespace BudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BudgetDetail
 *
 * @ORM\Table(name="budget_detail")
 * @ORM\Entity(repositoryClass="BudgetBundle\Repository\BudgetDetailRepository")
 */
class BudgetDetail
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
     * @ORM\ManyToOne(targetEntity="BudgetBundle\Entity\BaseBudget", inversedBy="budgetDetails")
     * @ORM\JoinColumn(name="budget_id", referencedColumnName="id")
     */
    private $budget;

    /**
     * @ORM\ManyToOne(targetEntity="BudgetBundle\Entity\BudgetHead")
     * @ORM\JoinColumn(name="budget_head_id", referencedColumnName="id")
     */
    private $budgetHead;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="decimal", precision=12, scale=3)
     */
    private $amount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="requestAmount", type="decimal", precision=12, scale=3)
     */
    private $requestAmount = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="remark", type="string", length=255, nullable=true)
     */
    private $remark;


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
     * Set budget
     *
     * @param BaseBudget $budget
     *
     * @return BudgetDetail
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return \stdClass
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return BudgetDetail
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
     * @return BudgetDetail
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
     * Set budgetHead
     *
     * @param BudgetHead $budgetHead
     *
     * @return $this
     */
    public function setBudgetHead(BudgetHead $budgetHead)
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
     * @return float
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param float $remark
     *
     * @return BudgetDetail
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }
}

