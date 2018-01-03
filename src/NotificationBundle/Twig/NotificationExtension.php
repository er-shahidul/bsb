<?php

namespace NotificationBundle\Twig;

use NotificationBundle\Manager\NotificationManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NotificationExtension extends \Twig_Extension
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var NotificationManager
     */
    private $notificationManager;

    public function __construct(TokenStorageInterface $tokenStorage, NotificationManager $notificationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->notificationManager = $notificationManager;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('get_notifications', array($this, 'getNotifications'))
        ];
    }

    public function getNotifications()
    {
        $user = $this->getUser();

        if($user == null) {
            return ['count' => 0, 'items' => [] ];
        }

        return $this->notificationManager->getNewNotifications($user);
    }

    private function getUser()
    {
        if($this->tokenStorage->getToken() == null) {
            return null;
        }

        return $this->tokenStorage->getToken()->getUser();
    }
}