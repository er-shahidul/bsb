<?php

namespace UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class PermissionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('user.permission_builder')) {
            return;
        }

        $definition = $container->getDefinition('user.permission_builder');
        $taggedServices = $container->findTaggedServiceIds('user.permission_provider');

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                    'addProvider',
                    array(new Reference($id), $attributes["alias"])
                );
            }
        }
    }
}