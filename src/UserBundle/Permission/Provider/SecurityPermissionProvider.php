<?php

namespace UserBundle\Permission\Provider;

class SecurityPermissionProvider implements ProviderInterface
{
    public function getPermissions()
    {
        $moduleRoles = [
            'ROLE_BUDGET',
            'ROLE_ACCOUNT',
            'ROLE_WELFARE',
            'ROLE_MEDICINE',
            'ROLE_MEDICAL'
        ];

        return [
            'ROLE_BUDGET_CLERK' => ['ROLE_BUDGET'],
            'ROLE_ACCOUNT_CLERK' => ['ROLE_ACCOUNT'],
            'ROLE_WELFARE_CLERK' => ['ROLE_WELFARE'],
            'ROLE_MEDICINE_CLERK' => ['ROLE_MEDICINE'],
            'ROLE_ESTABLISHMENT_CLERK' => ['ROLE_USER'],
            'ROLE_AO' => $moduleRoles,
            'ROLE_HEAD_CLERK' => $moduleRoles,
            'ROLE_DD' => $moduleRoles,
            'ROLE_DIRECTOR' => $moduleRoles,
            'ROLE_DASB_CLERK' => ['ROLE_USER'],
            'ROLE_IO' => ['ROLE_USER'],
            'ROLE_SECRETARY' => ['ROLE_USER'],
            'ROLE_DISPENSARY_CLERK' => ['ROLE_USER'],
            'ROLE_OFFICE_ADMIN' => ['ROLE_USER'],
            'ROLE_ADMIN' => ['ROLE_USER'],
            'ROLE_CHAIRMAN' => ['ROLE_BOARD_MEMBER'],
            'ROLE_BOARD_MEMBER' => ['ROLE_USER'],
        ];
    }
}