<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateGroupsCommand extends ContainerAwareCommand
{
    /**
     * @return array
     */
    public static function officeTypeWiseUserGroup()
    {
        return [
            "HQ"   => [
                "Establishment clerk" => ['ROLE_ESTABLISHMENT_CLERK'],
                "Budget Clerk"        => ['ROLE_BUDGET_CLERK'],
                "Account Clerk"       => ['ROLE_ACCOUNT_CLERK'],
                "Welfare Clerk"       => ['ROLE_WELFARE_CLERK'],
                "Medicine Clerk"      => ['ROLE_MEDICINE_CLERK'],
                "Head Clerk"          => ['ROLE_HEAD_CLERK'],
                "AO"                  => ['ROLE_AO'],
                "DD"                  => ['ROLE_DD'],
                "Director"            => ['ROLE_DIRECTOR'],
                "Office Admin"        => ['ROLE_OFFICE_ADMIN'],
                "Super Admin"         => ['ROLE_ADMIN']
            ],
            "DASB" => [
                "DASB Clerk"       => ['ROLE_DASB_CLERK'],
                "IO"               => ['ROLE_IO'],
                "Secretary"        => ['ROLE_SECRETARY'],
                "Dispensary Clerk" => ['ROLE_DISPENSARY_CLERK'],
            ],
        ];
    }

    protected function configure()
    {
        $this->setName('basb:populate:groups')
            ->setDescription('Populate BASB Core user groups.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->populateUserGroups();
        $output->writeln('<info>User groups has been populated successfully.</info>');
    }


    protected function getOrCreateUserGroup($groupName, $roles = array(), $officeType)
    {
        $groupManager = $this->getContainer()->get('fos_user.group_manager');
        $group = $groupManager->findGroupByName($groupName);


        if ($group == null) {
            $group = $groupManager->createGroup($groupName);
        }
        $group->setRoles($roles);
        $group->setOfficeType($officeType);
        $groupManager->updateGroup($group);

        return $group;
    }

    private function populateUserGroups()
    {
        $groupData = self::officeTypeWiseUserGroup();

        $officeTypeRepo = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:OfficeType');
        foreach ($groupData as $officeType => $groups) {
            $officeType = $officeTypeRepo->findOneBy(['name' => $officeType]);
            foreach ($groups as $name => $roles) {
                $this->getOrCreateUserGroup($name, $roles, $officeType);
            }
        }
    }
}
