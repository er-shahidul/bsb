<?php

namespace Devnet\PolicyManagerBundle;

use Devnet\PolicyManagerBundle\DependencyInjection\Compiler\PolicyManagerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DevnetPolicyManagerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new PolicyManagerCompilerPass());
    }
}
