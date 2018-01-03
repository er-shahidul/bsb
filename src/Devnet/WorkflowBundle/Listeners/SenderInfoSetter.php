<?php

namespace Devnet\WorkflowBundle\Listeners;

use AppBundle\Subscriber\LoggedinUserAwareSubscriber;
use Devnet\WorkflowBundle\Entity\TaskQueue;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class SenderInfoSetter extends LoggedinUserAwareSubscriber
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof TaskQueue) {
            return;
        }

        $user = $this->getLoggedinUser();

        if ($user === NULL) {
            return;
        }

        $entity
            ->setSenderDesignation($user->getDesignation())
            ->setSenderName($user->getName());
    }
}