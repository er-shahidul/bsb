<?php

namespace NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SystemNotification
 *
 * @ORM\Entity()
 */
class SystemNotification extends Notification
{
    public function getType()
    {
        return 'system';
    }

    public function getSender()
    {
        $sender = parent::getSender();
        $sender['image'] = 'assets/global/img/system.png';
        return $sender;
    }
}