<?php

namespace BudgetBundle\Permission\Provider;

use UserBundle\Permission\Provider\ProviderInterface;

class SecurityPermissionProvider implements ProviderInterface
{
    public function getPermissions()
    {
        return [];
    }
}