<?php

namespace NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

/**
 * NotificationRecipient
 *
 * @ORM\Table(name="notification_recipient")
 * @ORM\Entity(repositoryClass="NotificationBundle\Repository\NotificationRecipientRepository")
 */
class NotificationRecipient
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var array
     *
     * @ORM\Column(name="recipient", type="json_array")
     */
    private $recipient = ['id' => null, 'name' => '', 'image' => ''];

    /**
     * @var string
     *
     * @ORM\Column(name="recipient_name", type="string", length=255, nullable=true)
     */
    private $recipientName;

    /**
     * @var Notification
     *
     * @ORM\ManyToOne(targetEntity="Notification")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id")
     */
    private $notification;

    /**
     * @var bool
     *
     * @ORM\Column(name="seen", type="boolean")
     */
    private $seen = false;

    public function __construct($notification = null, User $user = null)
    {
        $this->notification = $notification;
        $this->setUser($user);
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
     * Set user
     *
     * @param User $user
     *
     * @return NotificationRecipient
     */
    public function setUser($user = NULL)
    {
        $this->user = $user;

        $this->recipient = [
            'id' => $user->getProfileId(),
            'name' => $user->getNameAndDesignation('__NAME__ (__DESIGNATION__)'),
            'image' => $user->getPhoto(),
        ];

        $this->recipientName = $this->recipient['name'];

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set notification
     *
     * @param \stdClass $notification
     *
     * @return NotificationRecipient
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Set seen
     *
     * @param boolean $seen
     *
     * @return NotificationRecipient
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen
     *
     * @return bool
     */
    public function isSeen()
    {
        return $this->seen;
    }

    /**
     * @return string
     */
    public function getRecipientName()
    {
        return $this->recipientName;
    }

    /**
     * @return array
     */
    public function getRecipient()
    {
        return empty($this->recipient) ? ['id' => null, 'name' => '', 'image' => ''] : $this->recipient;
    }
}

