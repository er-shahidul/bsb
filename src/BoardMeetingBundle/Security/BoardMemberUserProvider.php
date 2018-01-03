<?php

namespace BoardMeetingBundle\Security;

use BoardMeetingBundle\Entity\BoardMember;
use BoardMeetingBundle\Service\BoardManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class BoardMemberUserProvider implements UserProviderInterface
{
    /**
     * @var BoardManager
     */
    private $boardManager;

    public function __construct(BoardManager $boardManager)
    {
        $this->boardManager = $boardManager;
    }

    public function loadUserByUsername($token)
    {
        return $this->boardManager->getUser($token);
    }

    public function refreshUser(UserInterface $user)
    {
        return null;
    }

    public function supportsClass($class)
    {
        return BoardMember::class === $class;
    }
}