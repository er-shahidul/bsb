<?php

namespace Devnet\WorkflowBundle\Security;

use Devnet\WorkflowBundle\Core\WorkflowDefinitionRegistry;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Workflow\Registry;
use UserBundle\Entity\User;

class WorkflowVoter extends Voter
{
    const SEPARATOR = ':';
    const EDIT = 'edit';
    /**
     * @var WorkflowDefinitionRegistry
     */
    private $definitionRegistry;
    /**
     * @var Registry
     */
    private $registry;

    /** @var AccessDecisionManagerInterface */
    private $decisionManager;

    public function __construct(
        WorkflowDefinitionRegistry $workflowDefinitionRegistry,
        AccessDecisionManagerInterface $decisionManager,
        Registry $registry)
    {
        $this->decisionManager = $decisionManager;
        $this->definitionRegistry = $workflowDefinitionRegistry;
        $this->registry = $registry;
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
        list($action, $workflowName, $place) = explode(self::SEPARATOR, $attribute."::");

        if($action !== self::EDIT || empty($workflowName) || !$subject instanceof BaseWorkflowEntity) {
            return false;
        }

        if (!in_array($workflowName, $this->definitionRegistry->getWorkflowIds())) {
            return false;
        }

        if(empty($place)) {
           return true;
        }

        return in_array($place, $this->definitionRegistry->getEditablePlaces($workflowName));
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

        list($action, $workflowName, $place) = explode(self::SEPARATOR, $attribute."::");

        return $this->canEdit($subject, $workflowName, $place, $token);
    }

    private function canEdit(BaseWorkflowEntity $entity, $workflowName, $placeToCheck = null, TokenInterface $token)
    {
        $workflow = $this->registry->get($entity, $workflowName);


        $editablePlaces = $this->definitionRegistry->getEditablePlaces($workflowName);

        $isEditable = false;

        $currentPlaces = $workflow->getMarking($entity)->getPlaces();

        if(!empty($placeToCheck) && !array_key_exists($placeToCheck, $currentPlaces)) {
            return false;
        }

        foreach ($editablePlaces as $place) {

            if(array_key_exists($place, $currentPlaces)) {
                $isEditable = true;
                break;
            }
        }


        $transitions = PermissionUtil::getTransitionsFromCurrentMerking(
            $workflow->getDefinition()->getTransitions(),
            $workflow->getMarking($entity)
        );

        $roleGroup = [];

        foreach ($transitions as $transition) {

            $role = $this->definitionRegistry->getTransitionRole($workflowName, $transition->getName());

            if (empty($role)) {
                continue;
            }

            $roleGroup[$role] = 'ROLE_' . $role;

        }

        return $isEditable && count($roleGroup) > 0 && $this->decisionManager->decide($token, array_values($roleGroup));
   }
}
