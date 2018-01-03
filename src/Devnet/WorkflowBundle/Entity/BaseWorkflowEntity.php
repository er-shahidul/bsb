<?php

namespace Devnet\WorkflowBundle\Entity;

use AppBundle\Entity\Office;
use AppBundle\Entity\OfficeAwareEntityInterface;
use AppBundle\Traits\BlameableEntity;
use Devnet\WorkflowBundle\Core\WorkflowEntityInterface;
use Devnet\WorkflowBundle\Core\WorkflowStepRemark;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class BaseWorkflowEntity implements WorkflowEntityInterface, OfficeAwareEntityInterface
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
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="active_user_id")
     */
    private $activeUser;

    /**
     * @var array
     *
     * @ORM\Column(name="step_remarks", type="json_array", nullable=true)
     */
    private $stepRemarks = array();

    /** @var  WorkflowStepRemark */
    private $stepRemark;


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
     * @return Office
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
     * Set stepRemarks
     *
     * @param array $stepRemarks
     *
     * @return BaseWorkflowEntity
     */
    public function setStepRemarks($stepRemarks)
    {
        $this->stepRemarks = $stepRemarks;

        return $this;
    }

    /**
     * Get stepRemarks
     *
     * @return array
     */
    public function getStepRemarks()
    {
        return $this->stepRemarks;
    }

    public function getStepRemark()
    {
        return $this->stepRemark;
    }

    public function setStepRemark(WorkflowStepRemark $stepRemark)
    {
        $this->stepRemark = $stepRemark;
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function updateRemarks()
    {
        if (null === $this->stepRemark) {
            return;
        }

        array_unshift($this->stepRemarks, array(
            'remarks' => $this->stepRemark->getRemarks(),
            'place' => $this->stepRemark->getPlace(),
            'on' => $this->updatedAt->format('Y-m-d h:i:s a'),
            'by' => $this->getUpdatedByArray(),
        ));

        $this->stepRemark = null;
    }

    /**
     * @return array
     */
    private function getUpdatedByArray()
    {
        if ($this->updatedBy == null) {
            return ['fullName' => "System", 'designation' => "System", 'name' => "System"];
        }
        return array(
            'fullName' => $this->updatedBy->getName(),
            'designation' => $this->updatedBy->getDesignation(),
            'name' => $this->updatedBy->getUsername(),
        );
    }

    /**
     * @return mixed
     */
    public function getActiveUser()
    {
        return $this->activeUser;
    }

    /**
     * @param mixed $activeUser
     */
    public function setActiveUser($activeUser)
    {
        $this->activeUser = $activeUser;
    }

    public function skipInitialQueue()
    {
        return false;
    }

    /**
     * @param int $id
     * @return BaseWorkflowEntity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}

