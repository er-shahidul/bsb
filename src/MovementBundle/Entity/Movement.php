<?php

namespace MovementBundle\Entity;

use AppBundle\Entity\AttachmentsTrait;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Movement
 *
 * @ORM\Table(name="movements")
 * @ORM\Entity(repositoryClass="MovementBundle\Repository\MovementRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="movement_type", type="string")
 * @ORM\DiscriminatorMap({"base" = "Movement", "director" = "DirectorMovement", "general" = "GeneralMovement", "secretary" = "SecretaryMovement"})
 */
class Movement extends BaseWorkflowEntity
{
    use AttachmentsTrait;

    /**
     * @ORM\ManyToMany(targetEntity="PersonnelBundle\Entity\ServingPersonnel")
     * @ORM\JoinTable(name="join_movements_serving_personnel",
     *      joinColumns={@ORM\JoinColumn(name="movement_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="serving_personnel_id", referencedColumnName="id")}
     * )
     */
    private $visitors;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Office")
     * @ORM\JoinTable(name="join_movements_office",
     *      joinColumns={@ORM\JoinColumn(name="movement_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="office_id", referencedColumnName="id")}
     * )
     */
    private $destinations;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    protected $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime")
     */
    protected $endDate;

    /**
     * @var string
     * @ORM\Column(name="travel_by", type="string", length=200, nullable=true)
     */
    protected $travelBy;

    /**
     * @var string
     * @ORM\Column(name="start_point", type="string", length=200, nullable=true)
     */
    protected $startPoint;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", length=512, nullable=true)
     */
    protected $description;

    /**
     * @var string
     * @ORM\Column(name="movement_approval", type="string", length=512, nullable=true)
     */
    protected $movementApproval;

    /**
     * @var string
     * @ORM\Column(name="transportation", type="string", length=512, nullable=true)
     */
    protected $transportation;

    /**
     * @var string
     * @ORM\Column(name="travel_plan", type="string", length=512, nullable=true)
     */
    protected $travelPlan;

    /**
     * @var string
     * @ORM\Column(name="additional_members", type="string", length=512, nullable=true)
     */
    protected $additionalMembers;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Movement
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return string
     */
    public function getTravelBy()
    {
        return $this->travelBy;
    }

    /**
     * @param string $travelBy
     */
    public function setTravelBy($travelBy)
    {
        $this->travelBy = $travelBy;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getTravelPlan()
    {
        return $this->travelPlan;
    }

    /**
     * @param string $travelPlan
     */
    public function setTravelPlan($travelPlan)
    {
        $this->travelPlan = $travelPlan;
    }

    /**
     * @return mixed
     */
    public function getVisitors()
    {
        return $this->visitors;
    }

    /**
     * @param mixed $visitors
     */
    public function setVisitors($visitors)
    {
        $this->visitors = $visitors;
    }

    /**
     * @return string
     */
    public function getStartPoint()
    {
        return $this->startPoint;
    }

    /**
     * @param string $startPoint
     */
    public function setStartPoint($startPoint)
    {
        $this->startPoint = $startPoint;
    }

    /**
     * @return string
     */
    public function getAdditionalMembers()
    {
        return $this->additionalMembers;
    }

    /**
     * @param string $additionalMembers
     */
    public function setAdditionalMembers($additionalMembers)
    {
        $this->additionalMembers = $additionalMembers;
    }

    /**
     * @return mixed
     */
    public function getDestinations()
    {
        return $this->destinations;
    }

    /**
     * @param mixed $destinations
     */
    public function setDestinations($destinations)
    {
        $this->destinations = $destinations;
    }

    /**
     * @return string
     */
    public function getMovementApproval()
    {
        return $this->movementApproval;
    }

    /**
     * @param string $movementApproval
     */
    public function setMovementApproval($movementApproval)
    {
        $this->movementApproval = $movementApproval;
    }

    /**
     * @return string
     */
    public function getTransportation()
    {
        return $this->transportation;
    }

    /**
     * @param string $transportation
     */
    public function setTransportation($transportation)
    {
        $this->transportation = $transportation;
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

    public function getType()
    {
        return 'base';
    }
}

