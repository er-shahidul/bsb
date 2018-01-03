<?php

namespace UserBundle\Listeners;

use AppBundle\Entity\AuditLog;
use AppBundle\Subscriber\LoggedinUserAwareSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

/**
 * Listener responsible to change the redirection at the end of the password resetting
 */
class AuditLogProfileInfoSetter extends LoggedinUserAwareSubscriber
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof AuditLog) {
            return;
        }

        $user = $this->getLoggedinUser();

        if ($user === NULL) {
            return;
        }

        $entity->setProfileInfo($user->getNameAndDesig());
    }
}