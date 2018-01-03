<?php

namespace WelfareBundle\Entity;

use AppBundle\Entity\AttachmentsTrait;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MCDefaulterRegister
 *
 * @ORM\Table(name="micro_credit_defaulter_register")
 * @ORM\Entity(repositoryClass="WelfareBundle\Repository\MCDefaulterRegisterRepository")
 */
class MCDefaulterRegister extends BaseWorkflowEntity
{
    /**
     * @ORM\OneToMany(targetEntity="WelfareBundle\Entity\MCDefaulter", mappedBy="defaulterRegister")
     */
    private $defaulters;

    /**
     * @var array
     *
     * @ORM\Column(name="defaulter_remarks", type="json_array", nullable=true)
     */
    private $defaulterRemarks = array();

    public function __construct() {
        $this->features = new ArrayCollection();
        $this->defaulters = new ArrayCollection();
    }

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

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
    public function getDefaulters()
    {
        return $this->defaulters;
    }

    /**
     * @param mixed $defaulters
     */
    public function setDefaulters($defaulters)
    {
        $this->defaulters = $defaulters;
    }

    public function removeDefaulter($defaulter)
    {
        $this->defaulters->removeElement($defaulter);
        return $this;
    }

    /**
     * @return array
     */
    public function getDefaulterRemarks()
    {
        return $this->defaulterRemarks;
    }

    /**
     * @param array $defaulterRemarks
     */
    public function setDefaulterRemarks($defaulterRemarks)
    {
        $this->defaulterRemarks = $defaulterRemarks;
    }
}

