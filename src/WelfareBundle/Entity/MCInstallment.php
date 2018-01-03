<?php

namespace WelfareBundle\Entity;

use AppBundle\Traits\BlameableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Table(name="micro_credit_installment")
 * @ORM\Entity(repositoryClass="WelfareBundle\Repository\MCInstallmentRepository")
 */
class MCInstallment
{
    use BlameableEntity, TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Office")
     * @ORM\JoinColumn(name="office_id")
     */
    private $office;

    /**
     * @ORM\ManyToOne(targetEntity="WelfareBundle\Entity\MicroCreditApplication")
     * @ORM\JoinColumn(name="application_id")
     */
    private $application;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="due_date", type="date", nullable=true)
     */
    private $dueDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="installment_amount", type="integer")
     */
    private $installmentAmount = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="installment_number", type="smallint")
     */
    private $installmentNumber = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted = false;

    /**
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @param \DateTime $dueDate
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * @param mixed $office
     */
    public function setOffice($office)
    {
        $this->office = $office;
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
    public function getInstallmentNumber()
    {
        return $this->installmentNumber;
    }

    /**
     * @param int $installmentNumber
     */
    public function setInstallmentNumber($installmentNumber)
    {
        $this->installmentNumber = $installmentNumber;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }
}
