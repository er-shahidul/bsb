<?php

namespace UserBundle\Listeners;

use BoardMeetingBundle\Entity\BoardMember;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Role\SwitchUserRole;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use UserBundle\Entity\User;

/**
 * Listener responsible to change the redirection at the end of the password resetting
 */
class LoginListener implements EventSubscriberInterface
{
    /** @var  \Symfony\Component\HttpFoundation\Session\Session */
    private $session;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;
    /**
     * @var TokenStorageInterface
     */
    private $storage;

    public function __construct(Session $session, AuthorizationCheckerInterface $authorizationChecker,
                                TokenStorageInterface $storage)
    {
        $this->session = $session;
        $this->authorizationChecker = $authorizationChecker;
        $this->storage = $storage;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => [['onSecurityImplicitLogin', 900]],
            SecurityEvents::INTERACTIVE_LOGIN      => [['onSecurityInteractiveLogin', 900]],
            SecurityEvents::SWITCH_USER => [['checkSwitchAuthorisation', 0]]
        );
    }

    public function checkSwitchAuthorisation(SwitchUserEvent $switchEvent)
    {
        if (!$this->isCurrentUserAllowedToSwitchTo($switchEvent->getTargetUser())) {
            throw new AccessDeniedException();
        }
    }

    /**
     * Check if the current User (current token) is allowed to switch to the given User.
     *
     * @param User $user
     * @return bool
     */
    protected function isCurrentUserAllowedToSwitchTo($user)
    {
        if ($user->getProfile() !== NULL && !$user->hasInvalidOffice()) {
            return TRUE;
        }

        $impersonator = $this->getImpersonatingUser();

        return ($impersonator && $impersonator->getId() == $user->getId());
    }
    /**
     * Retrieves the User that is doing the impersonating by checking the roles in the current token.
     *
     * @return User
     */
    protected function getImpersonatingUser()
    {
        foreach ($this->storage->getToken()->getRoles() as $role) {
            if ($role instanceof SwitchUserRole) {
                return $role->getSource()->getUser();
            }
        }
        return null;
    }

    public function onSecurityImplicitLogin(UserEvent $event)
    {
        $this->validatePersonnelProfileCheck($event->getUser(), $event);
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        $this->validatePersonnelProfileCheck($user, $event);

    }

    /**
     * @param       $user
     * @param Event $event
     */
    protected function validatePersonnelProfileCheck($user, Event $event)
    {
        /** Disable audit entry for board meeting authentication  */
        if ($user instanceof BoardMember) {
            $event->stopPropagation();
            return;
        }

        if (!($user instanceof User)) {
            return;
        }

        $msg = null;

        if (!$this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN') && $user->getProfile() == NULL) {
            $msg = 'Sorry, the user account does not have any associate personnel profile. Please contact with administrator to assign you to the user account .';
        }

        if ($msg === NULL && !$this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN') && $user->hasInvalidOffice()) {
            $msg = 'Sorry, your office is not same as the user account. Please contact with administrator to update your profile.';
        }

        if($msg !== NULL) {
            $event->stopPropagation();
            $this->preventLoginWithMessage($msg);
        }
    }

    /**
     * @param $message
     */
    protected function preventLoginWithMessage($message)
    {
        $this->storage->setToken(NULL);
        $this->session->invalidate();
        $this->session->getFlashBag()->set(
            'error',
            $message
        );
    }
}