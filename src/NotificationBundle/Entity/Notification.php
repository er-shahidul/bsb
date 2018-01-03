<?php

namespace NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use UserBundle\Entity\User;

/**
 * Notification
 *
 * @ORM\Table(name="notifications")
 * @ORM\Entity(repositoryClass="NotificationBundle\Repository\NotificationRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="notification_type", type="string")
 * @ORM\DiscriminatorMap({"notification" = "Notification", "user" = "UserNotification", "office" = "OfficeNotification", "system" = "SystemNotification"})
 */
class Notification
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
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=512)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=1024, nullable=true)
     */
    private $link;

    /**
     * @var string comma separated channel(sms/email)
     *
     * @ORM\Column(name="channels", type="string", length=255, nullable=true)
     */
    private $channels;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var array
     *
     * @ORM\Column(name="sender", type="json_array")
     */
    private $sender = ['id' => null, 'name' => '', 'image' => ''];

    /**
     * @var string
     *
     * @ORM\Column(name="sender_name", type="string", length=255, nullable=true)
     */
    private $senderName;

    /**
     * @var array
     *
     * @ORM\Column(name="metaData", type="json_array", nullable=true)
     */
    private $metaData;

    /**
     * @var User $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by")
     */
    private $createdBy;

    public function __construct()
    {
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
     * Set subject
     *
     * @param string $subject
     *
     * @return Notification
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
     * Set message
     *
     * @param string $message
     *
     * @return Notification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Notification
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Notification
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set sender
     *
     * @param array $sender
     *
     * @return Notification
     */
    public function setSender($sender)
    {
        $this->sender = array_merge(['id' => null, 'name' => '', 'image' => ''], $sender);

        $this->senderName = $this->sender['name'];

        return $this;
    }

    /**
     * Get sender
     *
     * @return array
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set metaData
     *
     * @param array $metaData
     *
     * @return Notification
     */
    public function setMetaData($metaData)
    {
        $this->metaData = $metaData;

        return $this;
    }

    /**
     * Get metaData
     *
     * @return array
     */
    public function getMetaData()
    {
        return $this->metaData;
    }

    public function getType()
    {
        return null;
    }

    /**
     * @return bool
     */
    public function isOfficeNotification()
    {
        return $this instanceof OfficeNotification;
    }

    /**
     * @return bool
     */
    public function isUserNotification()
    {
        return $this instanceof UserNotification;
    }

    /**
     * @return bool
     */
    public function isSystemNotification()
    {
        return $this instanceof SystemNotification;
    }

    /**
     * Sets createdBy.
     *
     * @param  User $createdBy
     * @return $this
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Returns createdBy.
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @return string
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * @param string $channels
     * @return $this
     */
    public function setChannels($channels)
    {
        $this->channels = $channels;

        return $this;
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
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;
    }
}

