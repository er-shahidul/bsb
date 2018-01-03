<?php

namespace BoardMeetingBundle\Entity;

use AppBundle\Traits\BlameableEntity;
use BoardMeetingBundle\Core\BoardMeetingRelationTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * BoardMember
 *
 * @ORM\Table(name="board_members")
 * @ORM\Entity(repositoryClass="BoardMeetingBundle\Repository\BoardMemberRepository")
 */
class BoardMember
{
    use BlameableEntity, TimestampableEntity, BoardMeetingRelationTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="mobileNo", type="string", length=16)
     */
    private $mobileNo;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="designation", type="string", length=512)
     */
    private $designation;

    /**
     * @var BoardMeeting
     *
     * @ORM\ManyToOne(targetEntity="BoardMeetingBundle\Entity\BoardMeeting", inversedBy="members", cascade={"persist"})
     */
    protected $meeting;


    /**
     * @var BoardMeeting
     *
     * @ORM\OneToOne(targetEntity="BoardMeetingBundle\Entity\BoardMeeting", mappedBy="chairman", cascade={"persist"})
     */
    protected $chairmanOf;

    /** @var  boolean */
    protected $chairman = FALSE;

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
     * Set name
     *
     * @param string $name
     *
     * @return BoardMember
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
     * Set mobileNo
     *
     * @param string $mobileNo
     *
     * @return BoardMember
     */
    public function setMobileNo($mobileNo)
    {
        $this->mobileNo = $mobileNo;

        return $this;
    }

    /**
     * Get mobileNo
     *
     * @return string
     */
    public function getMobileNo()
    {
        return $this->mobileNo;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return BoardMember
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set designation
     *
     * @param string $designation
     *
     * @return BoardMember
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * @return BoardMeeting
     */
    public function getChairmanOf()
    {
        return $this->chairmanOf;
    }

    /**
     * @param BoardMeeting $chairmanOf
     */
    public function setChairmanOf($chairmanOf)
    {
        $chairmanOf->setChairman($this);
        $this->chairmanOf = $chairmanOf;
    }

    /**
     * @return mixed
     */
    public function isChairman()
    {
        return $this->chairman || $this->chairmanOf !== NULL;
    }

    public function getNameAndDesignation($template)
    {
        return str_replace(['__NAME__', '__DESIGNATION__'], [$this->getName(), $this->getDesignation()], $template);
    }

    /**
     * @param mixed $chairman
     */
    public function setChairman($chairman)
    {
        $this->chairman = $chairman;
    }

    public function getRoles()
    {
        return [$this->isChairman() ? 'ROLE_CHAIRMAN' : 'ROLE_BOARD_MEMBER'];
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return 'Meeting User: ' . $this->getName();
    }

    public function __toString()
    {
        return $this->getId() . "";
    }
}

