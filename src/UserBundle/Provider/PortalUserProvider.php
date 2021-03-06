<?php

namespace UserBundle\Provider;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use UserBundle\Manager\PortalUserManager;

class PortalUserProvider implements UserProviderInterface
{

    /** @var PortalUserManager */
    protected $userManager;

    public function __construct(PortalUserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function isAuthKeyValid($authKey)
    {
        // Look up the username based on the token in the database, via
        // an API call, or do something entirely different
        return $this->userManager->getUserByAuthKey($authKey);
    }

    public function loadUserByUsername($username)
    {
        return new User(
            $username,
            null,
            // the roles for the user - you may choose to determine
            // these dynamically somehow based on the user
            array('ROLE_PORTAL_USER')
        );
    }

    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }
}