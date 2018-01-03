<?php

namespace AppBundle\Subscriber;


use BoardMeetingBundle\Entity\BoardMember;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use UserBundle\Entity\User;

abstract class LoggedinUserAwareSubscriber implements EventSubscriber
{
    /**
     * @var TokenStorageInterface
     */
    protected $storage;

    public function __construct(TokenStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function getSubscribedEvents()
    {
        return array(
            'prePersist'
        );
    }

    /**
     * @return null|User|BoardMember
     */
    protected function getLoggedinUser()
    {
        if ($this->storage->getToken() === NULL) {
            return NULL;
        }

        $user = $this->storage->getToken()->getUser();

        if ($user === NULL || !($user instanceof User || $user instanceof BoardMember)) {
            return NULL;
        }

        return $user;
    }

    abstract public function prePersist(LifecycleEventArgs $args);
}