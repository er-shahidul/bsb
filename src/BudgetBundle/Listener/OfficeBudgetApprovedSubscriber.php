<?php

namespace BudgetBundle\Listener;

use AppBundle\Entity\Office;
use BudgetBundle\Entity\Budget;
use Doctrine\ORM\EntityManagerInterface;
use NotificationBundle\Manager\NotificationManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Registry;
use UserBundle\Entity\User;

class OfficeBudgetApprovedSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var Registry */
    protected $workflow;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var NotificationManager  */
    protected $notificationManager;

    /** @var  TokenStorage */
    protected $tokenStorage;

    /** @var  UrlGeneratorInterface */
    protected $router;

    public function __construct(
        EntityManagerInterface $entityManager,
        Registry $registry,
        EventDispatcherInterface $eventDispatcher,
        NotificationManager $notificationManager,
        UrlGeneratorInterface $router)
    {
        $this->em = $entityManager;
        $this->workflow = $registry;
        $this->eventDispatcher = $eventDispatcher;
        $this->notificationManager = $notificationManager;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.office_budget.entered.approved' => array(
                array('sendNotification', 10),
            ),
        );
    }

    public function sendNotification(Event $event)
    {
        /** @var Budget $budget */
        $budget = $event->getSubject();

        $msg = sprintf('Budget of Financial Year %s is Approved', $budget->getFinancialYear()->getLabel());
        $url = $this->router->generate('budget_view', ['id' => $budget->getId()]);
        $this->notificationManager->sendNotification($this->getUsers($budget->getOffice()), 'Budget Approved', $msg, $url, null);
    }

    protected function getUsers(Office $office)
    {
        $users = [];
        /** @var User $user */
        foreach ($office->getUsers() as $user) {
            if ($user->hasRole('ROLE_DASB_CLERK') || $user->hasRole('ROLE_IO')) {
                $users[] = $user;
            }
        }

        return $users;
    }
}