<?php

namespace AppBundle\Command;

class CreateAdminCommand extends CreateSuperAdminCommand
{
    protected function configure()
    {
        $this->setName('basb:populate:admin')
            ->setDescription('Populate BASB Admin User account.');
    }

    protected function getSuperAdminUserGroup()
    {
        return $this->getUserGroup('Super Admin', array('ROLE_ADMIN'));
    }
}
