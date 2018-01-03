<?php

namespace BudgetBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use UserBundle\Entity\User;

class BudgetAccessVoter extends Voter
{
    /** @var AccessDecisionManagerInterface */
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, ['BUDGET_VIEW', 'BUDGET_EDIT'])) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        if ($this->decisionManager->decide($token, ['ROLE_SUPER_ADMIN'])) {
            return true;
        }

        switch ($attribute) {
            case 'BUDGET_VIEW':
                return $this->canView($subject, $token);
            case 'BUDGET_EDIT':
                return $this->canEdit($subject, $token);
        }

        return false;
    }

    protected function canView($subject, $token)
    {
        return $this->decisionManager->decide($token, ['SA'], $subject);
    }

    protected function canEdit($subject, $token)
    {

    }
}
