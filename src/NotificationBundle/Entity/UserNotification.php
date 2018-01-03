<?php

namespace NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

/**
 * UserNotification
 *
 * @ORM\Entity()
 */
class UserNotification extends Notification
{
    public function getType()
    {
        return 'user';
    }

    public function setSenderUser(User $user) {
        $this->setSender([
            'id' => $user->getId(),
            'name' => $user->getNameAndDesignation('__NAME__ (__DESIGNATION__)'),
            'image' => $user->getPhoto()
        ]);
    }
}