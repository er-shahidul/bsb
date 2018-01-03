<?php

namespace NotificationBundle\Manager;

use AppBundle\Entity\Office;
use Devnet\WorkflowBundle\Event\NotificationEvent;
use Doctrine\ORM\EntityManagerInterface;
use NotificationBundle\Entity\Notification;
use NotificationBundle\Entity\NotificationRecipient;
use NotificationBundle\Entity\OfficeNotification;
use NotificationBundle\Entity\SystemNotification;
use NotificationBundle\Entity\UserNotification;
use NotificationBundle\Model\NotifiableRecipient;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use UserBundle\Entity\User;

class NotificationManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EntityManagerInterface $em, RouterInterface $router, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->router = $router;
        $this->dispatcher = $dispatcher;
    }

    public function sendNotification(array $recipients = array(), $subject, $message, $link = null, $channel = null, $sender = null)
    {
        if (empty($recipients)) {
            return;
        }

        $notification = $this->createNotificationEntity($sender);

        if ($notification == null) {
            return;
        }

        $notification
            ->setSubject($subject)
            ->setMessage($message)
            ->setChannels($channel)
            ->setLink($link);

        $this->em->persist($notification);

        $this->send($recipients, $notification);
    }

    public function sendUserNotificationByEmail($email, $subject, $message, $route, $parameter)
    {
        $user = $this->getUserRepository()->findOneBy(['emailCanonical' => $email]);

        if(empty($user)) {
            return;
        }

        $notification = $this->createNotificationEntity(NULL)
            ->setSubject($subject)
            ->setMessage($message)
            ->setLink($this->router->generate($route, $parameter));

        $this->em->persist($notification);
        $this->send([$user], $notification);
    }

    public function send(array $recipients = array(), Notification $notification)
    {
        $channels = $notification->getChannels();
        $channels = empty($channels) ? null : array_filter(explode(',', $channels), array($this, 'validChannels'));

        foreach ($recipients as $recipient) {
            if ($recipient instanceof User) {
                $sender = $notification->getSender();
                if ($sender['id'] == $recipient->getId()) {
                    continue;
                }
                $this->sendToUser($notification, $recipient);
            } elseif ($recipient instanceof Office) {
                $this->send($recipient->getUsers()->toArray(), $notification);
                continue;
            }

            $this->dispatchNotificationEvent($channels, $recipient);
        }

        $this->em->flush();
    }

    public function getNewNotifications(User $user)
    {
        $notificationList = [];

        $taskNotification = $this->getNewTaskNotification($user);

        if (null !== $taskNotification) {
            $notificationList[] = $taskNotification;
        }

        $notifications = $this->getRecipientRepository()->findNewNotificationsByUser($user);
        foreach ($notifications as $item) {
            /** @var NotificationRecipient $item */
            $notificationList[] = [
                'id' => $item->getId(),
                'time' => $item->getNotification()->getDate(),
                'message' => $item->getNotification()->getMessage(),
                'subject' => $item->getNotification()->getSubject(),
                'link' => $item->getNotification()->getLink(),
                'sender' => $this->getSenderObject($item->getNotification()),
            ];
        }

        return ['count' => count($notificationList), 'items' => $notificationList];
    }

    private function validChannels($channel)
    {
        return in_array($channel, ['sms', 'email']);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository|\NotificationBundle\Repository\NotificationRecipientRepository
     */
    private function getRecipientRepository()
    {
        return $this->em->getRepository('NotificationBundle:NotificationRecipient');
    }

    /**
     * @param $sender
     * @return OfficeNotification|SystemNotification|UserNotification|null
     */
    private function createNotificationEntity($sender)
    {
        if (empty($sender)) {
            $notification = new SystemNotification();
        } elseif ($sender instanceof User) {
            $notification = new UserNotification();
            $notification->setSenderUser($sender);
        } elseif ($sender instanceof Office) {
            $notification = new OfficeNotification();
            $notification->setSenderOffice($sender);
        } else {
            $notification = null;
        }
        return $notification;
    }

    private function getSenderObject(Notification $item)
    {
        $sender = $item->getSender();

        if ($item->isUserNotification()) {
            $sender['image'] = $this->getUserProfilePhoto($sender);
        } elseif ($item->isOfficeNotification()) {
            $sender['image'] = 'assets/global/img/office.png';
        } else {
            $sender['image'] = 'assets/global/img/system.png';
        }

        return $sender;
    }

    /**
     * @param $sender
     * @return string
     */
    private function getUserProfilePhoto($sender)
    {
        return $this->getUserRepository()->find($sender['id'])->getPhoto();
    }

    public function getNewNotificationCount(User $user)
    {
        return $this->em->getRepository('NotificationBundle:NotificationRecipient')->countUserNotification($user->getId());
    }

    private function getNewTaskNotification(User $user)
    {
        $myTask = $this->em->getRepository('DevnetWorkflowBundle:UserTask')->countUserTaskAndTime($user->getId());

        if ($myTask === null || $myTask['count'] == 0) {
            return null;
        }

        return [
            'id' => null,
            'message' => '',
            'time' => $myTask['time'],
            'subject' => sprintf('You have <strong>%d</strong> pending task', $myTask['count']),
            'link' => $this->router->generate('my-tasks'),
            'sender' => [
                'id' => null,
                'name' => 'System',
                'image' => 'assets/global/img/system.png'
            ],
        ];
    }

    /**
     * @param Notification $notification
     * @param User $recipient
     */
    private function sendToUser(Notification $notification, User $recipient)
    {
        $notificationRecipient = new NotificationRecipient($notification, $recipient);
        $this->em->persist($notificationRecipient);
    }

    /**
     * @param $channels
     * @param $recipient
     */
    private function dispatchNotificationEvent($channels, $recipient)
    {
        if ($channels !== null) {
            foreach ($channels as $channel) {
                if ($recipient instanceof NotifiableRecipient) {
                    $this->dispatchNotificationChannelEvent($recipient, $channel);
                } elseif ($recipient instanceof User) {
                    $this->dispatchNotificationChannelEventForUser($recipient, $channel);
                } else {
                    return;
                }
            }
        }
    }

    private function dispatchNotificationChannelEventForUser(User $user, $channel)
    {
        $cellphone = $email = null;

        switch ($channel) {
            case 'sms':
                $cellphone = $user->getMobileNumber();
                break;
            case 'email':
                $email = $user->getEmail();
                break;
        }

        $this->dispatchNotificationChannelEvent(new NotifiableRecipient(
            $user->getNameAndDesig(),
            $email,
            $cellphone
        ), $channel);
    }

    private function dispatchNotificationChannelEvent(NotifiableRecipient $recipient, $channel)
    {
        switch ($channel) {
            case 'sms':
                if (empty($recipient->getCellPhone())) {
                    return;
                }
                break;
            case 'email':
                if (empty($recipient->getEmail())) {
                    return;
                }
                break;
        }

        $this->dispatcher->dispatch('notification.' . $channel, new NotificationEvent($recipient));
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\UserBundle\Repository\UserRepository
     */
    private function getUserRepository()
    {
        return $this->em->getRepository('UserBundle:User');
    }
}