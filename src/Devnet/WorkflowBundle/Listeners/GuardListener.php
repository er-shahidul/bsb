<?php

namespace Devnet\WorkflowBundle\Listeners;

use Devnet\WorkflowBundle\Core\WorkflowDefinitionRegistry;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

class GuardListener implements EventSubscriberInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;
    /**
     * @var WorkflowDefinitionRegistry
     */
    private $definitionRegistry;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, WorkflowDefinitionRegistry $definitionRegistry)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->definitionRegistry = $definitionRegistry;
    }

    public function guardWorkflow(GuardEvent $event)
    {
        /** @var BaseWorkflowEntity $entity */
        $entity = $event->getSubject();

        if (!$entity instanceof BaseWorkflowEntity) {
            return;
        }

        $currentTransaction = $event->getTransition()->getName();
        $role = $this->definitionRegistry->getTransitionRole($event->getWorkflowName(), $currentTransaction);

        if ($role) {
            $event->setBlocked($this->isNotGranted($role));
        }
    }

    private function isNotGranted($role)
    {
        return !$this->authorizationChecker->isGranted('ROLE_' . $role);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'workflow.guard' => array('guardWorkflow'),
        );
    }
}