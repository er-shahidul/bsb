<?php

namespace Devnet\PolicyManagerBundle\DependencyInjection\Compiler;

use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class PolicyManagerCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(PolicyManager::class)) {
            return;
        }

        $definition = $container->getDefinition(PolicyManager::class);
        $taggedServices = $container->findTaggedServiceIds('app.policy_group');

        $policyGroups = array();

        foreach ($taggedServices as $serviceId => $tagAttributes) {
            $serviceDefinition = $container->getDefinition($serviceId);
            $class = $serviceDefinition->getClass();
            $policyGroups[$class::getNameSpace()] = new Reference($serviceId);
        }

        $definition->replaceArgument(2, $policyGroups);
    }
}