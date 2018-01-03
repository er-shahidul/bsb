<?php

namespace Devnet\WorkflowBundle\DependencyInjection\Compiler;

use Devnet\WorkflowBundle\Core\WorkflowDefinitionInterface;
use Devnet\WorkflowBundle\Core\WorkflowDefinitionRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Workflow;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\DependencyInjection\ChildDefinition;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class WorkflowCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->registerWorkflowConfiguration($container);
    }

    /**
     * Loads the workflow configuration.
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    private function registerWorkflowConfiguration(ContainerBuilder $container)
    {

        $registryDefinition = $container->getDefinition('workflow.registry');
        $workflowDefinitionRegistry = $container->getDefinition(WorkflowDefinitionRegistry::class);

        $workflowDefinitions = $this->buildSymfonyWorkflowCompilerPass($container, $registryDefinition);

        $workflowDefinitionRegistry->addArgument($workflowDefinitions);
    }

    /**
     * @param ContainerBuilder $container
     * @param $registryDefinition
     * @return mixed
     */
    private function buildSymfonyWorkflowCompilerPass(ContainerBuilder $container, $registryDefinition)
    {
        $workflowDefinitions = array();

        foreach ($container->findTaggedServiceIds('app.workflow_definition') as $serviceId => $tagAttributes) {
            $serviceDefinition = $container->getDefinition($serviceId);
            /** @var WorkflowDefinitionInterface $class */
            $class = $serviceDefinition->getClass();

            $type = $class::getType();
            $supports = $class::getSupports();
            $initialPlace = $class::getInitialPlace();
            $markingStoreConfig = $class::getMarkingStoreConfig();
            $name = $class::getName();
            $transitionsConfig = $class::getTransitionsConfig();
            $places = $class::getPlaces();
            $workflowDefinitions[$name] = new Reference($serviceId);

            $transitions = array();
            foreach ($transitionsConfig as $transitionName => $transition) {
                if ($type === 'workflow') {
                    $transitions[] = new Definition(Workflow\Transition::class, array($transitionName, $transition['from'], $transition['to']));
                } elseif ($type === 'state_machine') {
                    foreach ($transition['from'] as $from) {
                        foreach ($transition['to'] as $to) {
                            $transitions[] = new Definition(Workflow\Transition::class, array($transitionName, $from, $to));
                        }
                    }
                }
            }

            // Create a Definition
            $definitionDefinition = new Definition(Workflow\Definition::class);
            $definitionDefinition->setPublic(false);
            $definitionDefinition->addArgument($places);
            $definitionDefinition->addArgument($transitions);
            $definitionDefinition->addTag('workflow.definition', array(
                'name' => $name,
                'type' => $type,
                'marking_store' => isset($markingStoreConfig['type']) ? $markingStoreConfig['type'] : null,
            ));

            if ($initialPlace !== null) {
                $definitionDefinition->addArgument($initialPlace);
            }

            $markingStoreDefinition = new ChildDefinition('workflow.marking_store.'.$markingStoreConfig['type']);
            foreach ($markingStoreConfig['arguments'] as $argument) {
                $markingStoreDefinition->addArgument($argument);
            }

            // Create Workflow
            $workflowDefinition = new ChildDefinition(sprintf('%s.abstract', $type));
            $workflowDefinition->replaceArgument(0, $definitionDefinition);
            if (isset($markingStoreDefinition)) {
                $workflowDefinition->replaceArgument(1, $markingStoreDefinition);
            }
            $workflowDefinition->replaceArgument(3, $name);

            // Store to container
            $workflowId = sprintf('%s.%s', $type, $name);
            $container->setDefinition($workflowId, $workflowDefinition);
            $container->setDefinition(sprintf('%s.definition', $workflowId), $definitionDefinition);

            // Add workflow to Registry
            foreach ($supports as $supportedClassName) {
                $strategyDefinition = new Definition(Workflow\SupportStrategy\ClassInstanceSupportStrategy::class, array($supportedClassName));
                $strategyDefinition->setPublic(false);
                $registryDefinition->addMethodCall('add', array(new Reference($workflowId), $strategyDefinition));
            }

            // Add Guard Listener
            $guard = new Definition(Workflow\EventListener\GuardListener::class);
            $configuration = array();
            foreach ($transitionsConfig as $transitionName => $config) {
                if (!isset($config['guard'])) {
                    continue;
                }

                if (!class_exists(ExpressionLanguage::class)) {
                    throw new LogicException('Cannot guard workflows as the ExpressionLanguage component is not installed.');
                }

                $eventName = sprintf('workflow.%s.guard.%s', $name, $transitionName);
                $guard->addTag('kernel.event_listener', array('event' => $eventName, 'method' => 'onTransition'));
                $configuration[$eventName] = $config['guard'];
            }
            if ($configuration) {
                $guard->setArguments(array(
                    $configuration,
                    new Reference('workflow.security.expression_language'),
                    new Reference('security.token_storage'),
                    new Reference('security.authorization_checker'),
                    new Reference('security.authentication.trust_resolver'),
                    new Reference('security.role_hierarchy'),
                ));

                $container->setDefinition(sprintf('%s.listener.guard', $workflowId), $guard);
            }
        }
        return $workflowDefinitions;
    }
}
