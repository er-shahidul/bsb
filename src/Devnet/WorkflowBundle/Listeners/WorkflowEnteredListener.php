<?php

namespace Devnet\WorkflowBundle\Listeners;

use Devnet\WorkflowBundle\Core\WorkflowDefinitionRegistry;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Devnet\WorkflowBundle\Entity\GroupTask;
use Devnet\WorkflowBundle\Entity\UserTask;
use Devnet\WorkflowBundle\Security\PermissionUtil;
use Doctrine\Bundle\DoctrineBundle\Registry as DoctrineRegistry;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Registry as WorkflowRegistry;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;
use UserBundle\Entity\Group;
use UserBundle\Entity\User;
use UserBundle\Repository\GroupRepository;
use UserBundle\Repository\UserRepository;

class WorkflowEnteredListener implements EventSubscriberInterface
{
    /**
     * @var DoctrineRegistry
     */
    private $doctrine;
    /**
     * @var Workflow
     */
    private $workflow;
    /**
     * @var WorkflowDefinitionRegistry
     */
    private $definitionRegistry;

    /**
     * WorkflowEnteredListener constructor.
     * @param ManagerRegistry $doctrine
     * @param WorkflowRegistry $workflow
     * @param WorkflowDefinitionRegistry $definitionRegistry
     */
    public function __construct(ManagerRegistry $doctrine,
                                WorkflowRegistry $workflow,
                                WorkflowDefinitionRegistry $definitionRegistry)
    {
        $this->doctrine = $doctrine;
        $this->workflow = $workflow;
        $this->definitionRegistry = $definitionRegistry;
    }

    public function onEntered(Event $event)
    {
        $entity = $event->getSubject();

        if (!$entity instanceof BaseWorkflowEntity) {
            return;
        }

        $isInitialTransition = $this->isInitialTransition($event);

        if($isInitialTransition && $entity->skipInitialQueue()) {
            return;
        }

        $this->clearOldQueue($event);


        if ($isInitialTransition) {
            $transitions = $this->getInitialEnabledTransitions($event, $entity);
        } else {
            $transitions = $this->getEnabledTransitions($event);
        }

        $workflowName = $event->getWorkflowName();

        $roleGroup = [];
        foreach ($transitions as $transition) {

            $role = $this->definitionRegistry->getTransitionRole($workflowName, $transition->getName());

            if (empty($role)) {
                continue;
            }

            if (isset($roleGroup[$role])) { //already created for this user role
                continue;
            }

            $roleGroup[$role] = 'ROLE_' . $role;

            $this->createQueueEntry($roleGroup[$role], $event);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.entered' => 'onEntered',
        );
    }

    private function clearOldQueue(Event $event)
    {
        $this->getUserTaskRepository()->clearQueue($event->getWorkflowName(), $event->getSubject()->getId());
        $this->getGroupTaskRepository()->clearQueue($event->getWorkflowName(), $event->getSubject()->getId());
    }

    private function createQueueEntry($role, Event $event)
    {
        /** @var Group[] $groups */
        $groups = $this->getGroupRepository()->getAll();

        $userQueue = [];
        $groupQueue = [];
        $office = null;
        $singleUser = null;

        /** @var BaseWorkflowEntity $entity */
        $entity = $event->getSubject();

        if ($entity instanceof BaseWorkflowEntity) {
            $office = $entity->getOffice();
        }

        $em = $this->doctrine->getManager();

        foreach ($groups as $group) {
            if ($group->hasRole($role)) {
                $entityId = $entity->getId();
                $entityClass = get_class($entity);

                if (isset($groupQueue[$group->getId()])) {
                    continue;
                }

                $groupQueue[$group->getId()] = new GroupTask($group, $entityId, $event->getWorkflowName(), $entityClass, $office);
                $em->persist($groupQueue[$group->getId()]);

                foreach ($group->getUsers() as $user) {
                    /** @var User $user */
                    if (isset($userQueue[$user->getId()])) {
                        continue;
                    }

                    if ($entity instanceof BaseWorkflowEntity &&
                        $this->shouldIgnoreThisEntry($user, $entity, $this->isInitialTransition($event))
                    ) {
                        continue;
                    }

                    $singleUser = $user;
                    $userQueue[$user->getId()] = new UserTask($user, $entityId, $event->getWorkflowName(), $entityClass, $office);
                    $em->persist($userQueue[$user->getId()]);
                }
            }
        }

        if (count($userQueue) == 1) {
            $entity->setActiveUser($singleUser);
        } else {
            $entity->setActiveUser(null);
        }

        $em->flush();
    }

    /**
     * @return \Devnet\WorkflowBundle\Repository\UserTaskRepository
     */
    protected function getUserTaskRepository()
    {
        return $this->doctrine->getRepository('DevnetWorkflowBundle:UserTask');
    }

    /**
     * @return \Devnet\WorkflowBundle\Repository\GroupTaskRepository
     */
    protected function getGroupTaskRepository()
    {
        return $this->doctrine->getRepository('DevnetWorkflowBundle:GroupTask');
    }

    /**
     * @return GroupRepository;
     */
    protected function getGroupRepository()
    {
        return $this->doctrine->getRepository('UserBundle:Group');
    }

    /**
     * @return UserRepository;
     */
    protected function getUserRepository()
    {
        return $this->doctrine->getRepository('UserBundle:User');
    }

    /**
     * @param Event $event
     * @return Transition[]
     */
    protected function getEnabledTransitions(Event $event)
    {
        $workflow = $this->getWorkflow($event);

        return PermissionUtil::getTransitionsFromCurrentMerking(
            $workflow->getDefinition()->getTransitions(),
            $workflow->getMarking($event->getSubject())
        );

    }

    /**
     * @param Event $event
     * @return Workflow
     */
    protected function getWorkflow(Event $event)
    {
        return $this->workflow->get($event->getSubject(), $event->getWorkflowName());
    }

    /**
     * @param $user
     * @param $entity
     * @return bool
     */
    private function isRequestForDifferentOffice(User $user, BaseWorkflowEntity $entity)
    {
        return $user->getOffice() != $entity->getOffice();
    }

    private function shouldIgnoreThisEntry(User $user, BaseWorkflowEntity $entity, $initial)
    {
        $isRequestForDifferentOffice = $this->isRequestForDifferentOffice($user, $entity);

        return ($isRequestForDifferentOffice && $initial) || ($isRequestForDifferentOffice && $user->getOffice()->getOfficeType()->getName() == 'DASB');
    }

    /**
     * @param Event $event
     * @return bool
     */
    private function isInitialTransition(Event $event)
    {
        return $event->getTransition()->getName() == '_initialize';
    }

    /**
     * @param Event $event
     * @param $entity
     * @return Transition[]
     */
    protected function getInitialEnabledTransitions(Event $event, $entity)
    {
        try {
            $transitions = $this->getWorkflow($event)->getEnabledTransitions($entity);
        } catch (AuthenticationCredentialsNotFoundException $e) {
            $transitions = $this->getEnabledTransitions($event);
        }

        return $transitions;
    }
}