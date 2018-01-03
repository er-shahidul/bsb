<?php

namespace UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use UserBundle\DependencyInjection\Compiler\PermissionCompilerPass;

class UserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new PermissionCompilerPass());
    }
}