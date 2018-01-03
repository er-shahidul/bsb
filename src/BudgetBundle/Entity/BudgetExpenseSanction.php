<?php

namespace BudgetBundle\Entity;

use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * BudgetExpenseSantion
 *
 * @ORM\Table(name="budget_expense_sanction")
 * @ORM\Entity(repositoryClass="BudgetBundle\Repository\BudgetExpenseSanctionRepository")
 */
class BudgetExpenseSanction extends BaseWorkflowEntity
{
    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", nullable=true)
     */
    private $amount = 0.0;

    /**
     * @var integer
     *
     * @ORM\Column(name="vat", type="float")
     */
    private $vat = 0.0;

    /**
     * @var integer
     *
     * @ORM\Column(name="tax", type="float")
     */
    private $tax = 0.0;

    /**
     * @var float
     *
     * @ORM\Column(name="total_amount", type="float")
     */
    private $totalAmount = 0.0;

    /**
     * @var string
     *
     * @ORM\Column(name="cheque_lipi_no", type="string", length=255, nullable=true)
     */
    private $chequeLipiNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cheque_lipi_date", type="date", nullable=true)
     */
    private $chequeLipiDate;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity="BudgetBundle\Entity\BudgetExpense")
     * @ORM\JoinColumn(name="budget_expense_id", referencedColumnName="id")
     */
    private $budgetExpense;

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return BudgetExpenseSanction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getChequeLipiNo()
    {
        return $this->chequeLipiNo;
    }

    /**
     * @param string $chequeLipiNo
     *
     * @return BudgetExpenseSanction
     */
    public function setChequeLipiNo($chequeLipiNo)
    {
        $this->chequeLipiNo = $chequeLipiNo;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getChequeLipiDate()
    {
        return $this->chequeLipiDate;
    }

    /**
     * @param \DateTime $chequeLipiDate
     *
     * @return BudgetExpenseSanction
     */
    public function setChequeLipiDate($chequeLipiDate)
    {
        $this->chequeLipiDate = $chequeLipiDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBudgetExpense()
    {
        return $this->budgetExpense;
    }

    /**
     * @param mixed $budgetExpense
     *
     * @return BudgetExpenseSanction
     */
    public function setBudgetExpense($budgetExpense)
    {
        $this->budgetExpense = $budgetExpense;

        return $this;
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
     * @return BudgetExpenseSanction
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param int $vat
     *
     * @return BudgetExpenseSanction
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * @return int
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param int $tax
     *
     * @return BudgetExpenseSanction
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @param float $totalAmount
     *
     * @return BudgetExpenseSanction
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function calculateTotalAmount()
    {
        $this->setTotalAmount(
            $this->getAmount() +
            $this->getVat() +
            $this->getTax()
        );
    }

    public function skipInitialQueue()
    {
        return true;
    }


}