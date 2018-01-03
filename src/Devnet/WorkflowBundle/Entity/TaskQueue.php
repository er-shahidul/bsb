<?php

namespace Devnet\WorkflowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use UserBundle\Entity\User;

/**
 * @ORM\MappedSuperclass
 */
abstract class TaskQueue
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
     * @var int
     *
     * @ORM\Column(name="ref_id", type="bigint")
     */
    private $refId;

    /**
     * @var string
     *
     * @ORM\Column(name="module_id", type="text", length=50)
     */
    private $moduleId;

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="text", length=255)
     */
    private $entity;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Office")
     * @ORM\JoinColumn(name="office_id", referencedColumnName="id")
     */
    private $office;

    /**
     * @var User $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="from_user")
     */
    private $fromUser;

    /**
     * @var string
     *
     * @ORM\Column(name="sender_name", type="text", length=255, nullable=true)
     */
    private $senderName;

    /**
     * @var string
     *
     * @ORM\Column(name="sender_designation", type="text", length=255, nullable=true)
     */
    private $senderDesignation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * TaskQueue constructor.
     *
     * @param int    $refId
     * @param string $moduleId
     * @param string $entity
     * @param        $office
     */
    public function __construct($refId, $moduleId, $entity, $office)
    {
        $this->refId = $refId;
        $this->moduleId = $moduleId;
        $this->entity = $entity;
        $this->office = $office;
        $this->date = new \DateTime();
    }


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
     * @return int
     */
    public function getRefId()
    {
        return $this->refId;
    }

    /**
     * @param int $refId
     */
    public function setRefId($refId)
    {
        $this->refId = $refId;
    }

    /**
     * @return mixed
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * @param mixed $moduleId
     */
    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param string $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
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
     * @return User
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * @param User $fromUser
     */
    public function setFromUser($fromUser)
    {
        $this->fromUser = $fromUser;
    }

    /**
     * @param \DateTime $date
     *
     * @return TaskQueue
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * @param string $senderName
     *
     * @return $this
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;

        return $this;
    }

    /**
     * @return string
     */
    public function getSenderDesignation()
    {
        return $this->senderDesignation;
    }

    /**
     * @param string $senderDesignation
     *
     * @return $this
     */
    public function setSenderDesignation($senderDesignation)
    {
        $this->senderDesignation = $senderDesignation;

        return $this;
    }
}

