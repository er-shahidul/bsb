<?php

namespace WelfareBundle\Entity;

use AppBundle\Entity\AttachmentsTrait;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MicroCreditPayment
 *
 * @ORM\Table(name="micro_credit_payment")
 * @ORM\Entity(repositoryClass="WelfareBundle\Repository\MicroCreditPaymentRepository")
 */
class MicroCreditPayment extends BaseWorkflowEntity
{
    use AttachmentsTrait;

    /**
     * @ORM\ManyToOne(targetEntity="WelfareBundle\Entity\MicroCreditApplication")
     * @ORM\JoinColumn(name="application_id")
     */
    private $application;

    /**
     * @var integer
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "Invalid amount",
     * )
     *
     * @ORM\Column(name="payment_amount", type="integer")
     */
    private $paymentAmount = 0;

    /**
     * @var \DateTime
     * @Assert\Date()
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="reference_no", type="string", length=255, nullable=true)
     */
    private $referenceNo;

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
     * @return int
     */
    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }

    /**
     * @param int $paymentAmount
     */
    public function setPaymentAmount($paymentAmount)
    {
        $this->paymentAmount = $paymentAmount;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
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
    public function getReferenceNo()
    {
        return $this->referenceNo;
    }

    /**
     * @param string $referenceNo
     */
    public function setReferenceNo($referenceNo)
    {
        $this->referenceNo = $referenceNo;
    }
}

