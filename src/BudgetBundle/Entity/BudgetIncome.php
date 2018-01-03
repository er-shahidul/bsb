<?php

namespace BudgetBundle\Entity;

use AppBundle\Entity\AttachmentsTrait;
use AppBundle\Entity\FinancialYear;
use AppBundle\Entity\OfficeAwareEntityInterface;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * BudgetExpense
 *
 * @ORM\Table(name="budget_income")
 * @ORM\Entity(repositoryClass="BudgetBundle\Repository\BudgetIncomeRepository")
 */
class BudgetIncome extends BaseWorkflowEntity implements OfficeAwareEntityInterface
{

    use AttachmentsTrait;

    /**
     * @ORM\ManyToOne(targetEntity="BudgetBundle\Entity\BudgetIncomeHead")
     * @ORM\JoinColumn(name="budget_income_head_id", referencedColumnName="id")
     */
    private $budgetHead;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount = 0.0;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FinancialYear")
     * @ORM\JoinColumn(name="financial_year_id", referencedColumnName="id")
     */
    private $financialYear;

    /**
     * @var string
     *
     * @ORM\Column(name="letter_no", type="string", length=255, nullable=true)
     */
    private $letterNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="letter_date", type="date")
     */
    private $letterDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status = 'draft';

    public function __construct()
    {
        $this->attachments = new  ArrayCollection();
    }

    /**
     * Set budgetHead
     *
     * @param \stdClass $budgetHead
     *
     * @return BudgetExpense
     */
    public function setBudgetHead($budgetHead)
    {
        $this->budgetHead = $budgetHead;

        return $this;
    }

    /**
     * Get budgetHead
     *
     * @return \stdClass
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
     * @return BudgetExpense
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
     * @return FinancialYear
     */
    public function getFinancialYear()
    {
        return $this->financialYear;
    }

    /**
     * @param mixed $financialYear
     *
     * @return BudgetExpense
     */
    public function setFinancialYear(FinancialYear $financialYear)
    {
        $this->financialYear = $financialYear;

        return $this;
    }

    /**
     * @return string
     */
    public function getLetterNo()
    {
        return $this->letterNo;
    }

    /**
     * @param string $letterNo
     *
     * @return BudgetExpense
     */
    public function setLetterNo($letterNo)
    {
        $this->letterNo = $letterNo;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLetterDate()
    {
        return $this->letterDate;
    }

    /**
     * @param \DateTime $letterDate
     *
     * @return BudgetExpense
     */
    public function setLetterDate($letterDate)
    {
        $this->letterDate = $letterDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $description
     *
     * @return BudgetExpense
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return BudgetExpense
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}