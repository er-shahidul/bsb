<?php

namespace BoardMeetingBundle\Entity;

use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * BoardMeeting
 *
 * @ORM\Table(name="board_meetings")
 * @ORM\Entity(repositoryClass="BoardMeetingBundle\Repository\BoardMeetingRepository")
 */
class BoardMeeting extends BaseWorkflowEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="meeting_type", type="string", length=50, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50, nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="meeting_date", type="date", length=255)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="text", length=255)
     */
    private $entity;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BoardMeetingBundle\Entity\BoardMember", mappedBy="meeting", cascade={"persist"}, orphanRemoval=true )
     * @ORM\OrderBy({"id" = "asc"})
     */
    protected $members;


    /**
     * @var BoardMember
     *
     * @ORM\OneToOne(targetEntity="BoardMeetingBundle\Entity\BoardMember", inversedBy="chairmanOf", cascade={"persist"})
     */
    protected $chairman;

    /**
     * BoardMeeting constructor.
     *
     */
    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return BoardMeeting
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
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
     *
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

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
     * @param string $date
     *
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param BoardMember $member
     *
     * @return $this
     */
    public function addMember($member)
    {
        if (!$this->members->contains($member)) {
            $member->setMeeting($this);
            $this->members->add($member);
        }

        return $this;
    }

    /**
     * @param BoardMember $member
     *
     * @return $this
     */
    public function removeMember($member)
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
        }

        return $this;
    }

    /**
     * @return BoardMember
     */
    public function getChairman()
    {
        return $this->chairman;
    }

    /**
     * @param BoardMember $chairman
     *
     * @return $this
     */
    public function setChairman($chairman)
    {
        $chairman->setChairman(TRUE);
        $this->addMember($chairman);
        $this->chairman = $chairman;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public static function create($entity)
    {
        return (new static())
            ->setEntity($entity)
            ->setChairman(new BoardMember())
        ;
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
}