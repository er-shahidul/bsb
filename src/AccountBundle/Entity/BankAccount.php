<?php

namespace AccountBundle\Entity;

use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * BankAccount
 *
 * @ORM\Table(name="bank_account")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\BankAccountRepository")
 */
class BankAccount extends BaseWorkflowEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="accountNumber", type="string", length=255)
     */
    private $accountNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status = 'draft';

    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\BankBranch")
     */
    private $bankBranch;

    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\FundType")
     */
    private $fundType;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return BankAccount
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set accountNumber
     *
     * @param string $accountNumber
     *
     * @return BankAccount
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @return mixed
     */
    public function getFundType()
    {
        return $this->fundType;
    }

    /**
     * @param mixed $fundType
     *
     * @return BankAccount
     */
    public function setFundType($fundType)
    {
        $this->fundType = $fundType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankBranch()
    {
        return $this->bankBranch;
    }

    /**
     * @param mixed $bankBranch
     *
     * @return BankAccount
     */
    public function setBankBranch($bankBranch)
    {
        $this->bankBranch = $bankBranch;

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
     * @return BankAccount
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}

