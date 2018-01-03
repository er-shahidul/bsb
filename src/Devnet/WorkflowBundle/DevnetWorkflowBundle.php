<?php

namespace Devnet\WorkflowBundle;

use Devnet\WorkflowBundle\DependencyInjection\Compiler\WorkflowCompilerPass;
use Symfony\Component\Config\Resource\ClassExistenceResource;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Workflow\DependencyInjection\ValidateWorkflowsPass;

class DevnetWorkflowBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new WorkflowCompilerPass());
        $this->addCompilerPassIfExists($container, ValidateWorkflowsPass::class);
    }

    private function addCompilerPassIfExists(ContainerBuilder $container, $class, $type = PassConfig::TYPE_BEFORE_OPTIMIZATION, $priority = 0)
    {
        $container->addResource(new ClassExistenceResource($class));

        if (class_exists($class)) {
            $container->addCompilerPass(new $class(), $type, $priority);
        }
    }
}