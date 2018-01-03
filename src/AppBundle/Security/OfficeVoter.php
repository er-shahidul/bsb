<?php

namespace AppBundle\Security;

use AppBundle\Entity\Office;
use AppBundle\Entity\OfficeAwareEntityInterface;
use AppBundle\Entity\OfficeType;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use UserBundle\Entity\User;

class OfficeVoter extends Voter
{
    /** @var AccessDecisionManagerInterface */
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, ['BASB_USER', 'DASB_USER', 'SAME_OFFICE'])) {
            return false;
        }

        if($attribute == 'SAME_OFFICE' && (empty($subject) || !$subject instanceof OfficeAwareEntityInterface) ) {
            throw new \InvalidArgumentException('The subject must be an Instance of ' . OfficeAwareEntityInterface::class);
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

        if($this->decisionManager->decide($token, ['ROLE_SUPER_ADMIN'])) {
            return true;
        }

        $office = $user->getOffice();

        if($office === null) {
            return false;
        }

        switch ($attribute) {
            case 'BASB_USER':
                return $this->isBasbUser($office->getOfficeType());
            case 'DASB_USER':
                return $this->isDasbUser($office->getOfficeType());
            case 'SAME_OFFICE':
                return $this->isSameOffice($subject, $office);
        }

        return false;
    }

    private function isBasbUser(OfficeType $type)
    {
        return $type->getName() === 'HQ';
    }

    private function isDasbUser(OfficeType $type)
    {
        return $type->getName() === 'DASB';
    }

    private function isSameOffice(OfficeAwareEntityInterface $entity, Office $office)
    {
        return $office === $entity->getOffice();
    }
}
