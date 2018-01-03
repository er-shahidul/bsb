<?php

namespace BoardMeetingBundle\Security;

use BoardMeetingBundle\Entity\BoardMember;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MeetingVoter extends Voter
{
    /** @var AccessDecisionManagerInterface */
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        if ($attribute !== 'BOARD_MEETING') {
            return false;
        }

        return TRUE;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        return $token->getUser() instanceof BoardMember;
    }
}
