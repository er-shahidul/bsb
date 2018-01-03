<?php

namespace BoardMeetingBundle\Listener;

use BoardMeetingBundle\Entity\BoardMeeting;
use BoardMeetingBundle\Entity\BoardMember;
use BoardMeetingBundle\Service\BoardManager;
use NotificationBundle\Manager\NotificationManager;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class BoardMeetingApprovedSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var TwigEngine
     */
    private $twig;
    /**
     * @var NotificationManager
     */
    private $notificationManager;
    /**
     * @var BoardManager
     */
    private $manager;
    /**
     * @var
     */
    private $adminEmail;
    /**
     * @var
     */
    private $adminName;

    public function __construct(\Swift_Mailer $mailer,
                                EngineInterface $twig,
                                NotificationManager $notificationManager,
                                BoardManager $manager,
                                $adminEmail,
                                $adminName
    )
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->notificationManager = $notificationManager;
        $this->manager = $manager;
        $this->adminEmail = $adminEmail;
        $this->adminName = $adminName;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.board_meeting.entered.approved' => array(
                array('notifyUsers', 10)
            )
        );
    }

    public function notifyUsers(Event $event)
    {
        $meeting = $event->getSubject();
        if (!$meeting instanceof BoardMeeting) {
            return;
        }

        /** @var BoardMember $member */
        foreach ($meeting->getMembers() as $member) {
            $this->emailLinksToUsers($member);
        }
    }

    private function emailLinksToUsers(BoardMember $member)
    {
        $meeting = $member->getMeeting();
        $token = $this->manager->generateToken($member);

        $message = (new \Swift_Message($meeting->getSubject()))
            ->setFrom($this->adminEmail, $this->adminName)
            ->setTo($member->getEmail(), $member->getName())
            ->setBody(
                $this->renderView(
                    '@BoardMeeting/Emails/meeting-email.twig',
                    [
                        'meeting' => $meeting,
                        'member' => $member,
                        'token' => $token,
                    ]
                ),
                'text/html'
            );

        $this->mailer->send($message);

        $this->notifyIfSystemUser($member, $meeting, $token);
    }

    /**
     * Returns a rendered view.
     *
     * @param string $view       The view name
     * @param array  $parameters An array of parameters to pass to the view
     *
     * @return string The rendered view
     */
    protected function renderView($view, array $parameters = array())
    {
        return $this->twig->render($view, $parameters);
    }

    /**
     * @param BoardMember $member
     * @param             $meeting
     * @param             $token
     */
    private function notifyIfSystemUser(BoardMember $member, BoardMeeting $meeting, $token)
    {
        $this->notificationManager->sendUserNotificationByEmail(
            $member->getEmail(),
            $meeting->getSubject(),
            sprintf('You have a meeting to attend on %s', $meeting->getDate()->format('jS M, Y')),
            'board_meeting_authenticate',
            ['member' => $member->getId(), '_token' => $token]
        );
    }
}