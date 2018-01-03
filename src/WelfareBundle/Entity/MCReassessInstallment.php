<?php

namespace WelfareBundle\Entity;

use AppBundle\Entity\AttachmentsTrait;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MCReassessInstallment
 *
 * @ORM\Table(name="micro_credit_reassess_installment")
 * @ORM\Entity(repositoryClass="WelfareBundle\Repository\MCReassessInstallmentRepository")
 */
class MCReassessInstallment extends BaseWorkflowEntity
{
    use AttachmentsTrait;

    /**
     * @ORM\ManyToOne(targetEntity="WelfareBundle\Entity\MicroCreditApplication")
     * @ORM\JoinColumn(name="application_id")
     */
    private $application;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text")
     * @Assert\NotBlank()
     */
    private $note;

    /**
     * @var integer
     *
     * @ORM\Column(name="installment_amount", type="integer")
     */
    private $installmentAmount = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
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
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return int
     */
    public function getInstallmentAmount()
    {
        return $this->installmentAmount;
    }

    /**
     * @param int $installmentAmount
     */
    public function setInstallmentAmount($installmentAmount)
    {
        $this->installmentAmount = $installmentAmount;
    }

    public function skipInitialQueue()
    {
        return true;
    }
}

