<?php

namespace NotificationBundle\Entity;

use AppBundle\Entity\Office;
use Doctrine\ORM\Mapping as ORM;

/**
 * OfficeNotification
 *
 * @ORM\Entity()
 */
class OfficeNotification extends Notification
{
    public function getType()
    {
        return 'office';
    }

    public function setSenderOffice(Office $office) {
        $this->setSender([
            'id' => $office->getId(),
            'name' => $office->getName()
        ]);
    }

    public function getSender()
    {
        $sender = parent::getSender();
        $sender['image'] = 'assets/global/img/office.png';
        return $sender;
    }
}