<?php

namespace AppBundle\Command;

use AppBundle\Entity\Office;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManager;
use MedicalBundle\Entity\Dispensary;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UserBundle\Entity\User;

class PopulateUsersCommand extends ContainerAwareCommand
{
    /** @var  EntityManager */
    protected $em;

    protected function configure()
    {
        $this->setName('basb:demo:user-populate')
            ->setDescription('Populate demo user data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $groupList = PopulateGroupsCommand::officeTypeWiseUserGroup();

        $userGroup = array();

        foreach ($groupList as $type => $groups) {
            foreach ($groups as $name => $roles) {
                $userGroup[$type][$name] = $this->getUserGroup($name, $roles);
            }
        }

        $userManager = $this->getContainer()->get('fos_user.user_manager');

        /** @var Office $office */
        foreach ($this->getOffices() as $office) {
            $this->createUserForOffice($office, $userGroup, $userManager);
        }
        $this->em->flush();
    }

    protected function createUser(UserManager $userManager, $userName, $designation)
    {
        $email = $userName . '@abcd.com';
        $password = '123456';

        $user = $userManager->createUser();

        $user->setUsername($userName);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled(TRUE);

        return $user;
    }

    protected function getUserGroup($groupName, $roles = array())
    {
        $groupManager = $this->getContainer()->get('fos_user.group_manager');
        $group = $groupManager->findGroupByName($groupName);

        if (!$group) {
            $group = $groupManager->createGroup($groupName);
            $group->setRoles($roles);
            $groupManager->updateGroup($group, FALSE);
        }

        return $group;
    }

    protected function getOffices()
    {
        return $this->em->getRepository('AppBundle:Office')->findAll();
    }

    /**
     * @param Office      $office
     * @param             $userGroups
     * @param UserManager $userManager
     */
    protected function createUserForOffice(Office $office, $userGroups, UserManager $userManager)
    {
        $dispensaries = $this->getDispensaries();
        foreach ($userGroups[$office->getOfficeType()->getName()] as $name => $group) {

            $officeName = $office->getName();

            if ('Dispensary Clerk' == $name) {
                $this->createUsersForDispensary($office, $userGroups, $userManager, $dispensaries, $officeName, $name);
                continue;
            }

            $user = $this->createOrGetUserObject($userManager, $officeName, $name);

            $this->saveOrUpdateUserObject($office, $userGroups, $userManager, $user, $name);
        }
    }

    /**
     * @param UserManager $userManager
     * @param             $userBaseName
     * @param             $name
     *
     * @return User
     */
    private function createOrGetUserObject(UserManager $userManager, $userBaseName, $name)
    {
        $userName = strtolower(str_replace(' ', '_', $userBaseName . "_" . $name));
        /** @var User $user */
        $user = $userManager->findUserByUsername($userName);

        if ($user == NULL) {
            $user = $this->createUser($userManager, $userName, $name);
        }

        return $user;
    }

    /**
     * @param Office      $office
     * @param             $userGroups
     * @param UserManager $userManager
     * @param             $user
     * @param             $name
     */
    private function saveOrUpdateUserObject(Office $office, $userGroups, UserManager $userManager, User $user, $name)
    {
        $user->setOffice($office);
        $user->addGroup($userGroups[$office->getOfficeType()->getName()][$name]);
        $userManager->updateUser($user);
    }

    private function getDispensaries()
    {
        $returns = [];

        $dispensaries = $this->em->getRepository('MedicalBundle:Dispensary')->findAll();
        foreach ($dispensaries as $dispensary) {
            if (!isset($returns[$dispensary->getOffice()->getName()])) {
                $returns[$dispensary->getOffice()->getName()] = [];
            }

            $returns[$dispensary->getOffice()->getName()][$dispensary->getName()] = $dispensary;
        }

        return $returns;
    }

    /**
     * @param Office      $office
     * @param             $userGroups
     * @param UserManager $userManager
     * @param             $dispensaries
     * @param             $officeName
     * @param             $name
     *
     */
    private function createUsersForDispensary(Office $office, $userGroups, UserManager $userManager, $dispensaries, $officeName, $name)
    {
        if(!isset($dispensaries[$officeName])) {
            return;
        }
        /** @var Dispensary $dispensary */
        foreach ($dispensaries[$officeName] as $dispensary) {
            $user = $this->createOrGetUserObject($userManager, $dispensary->getName(), $name);
            $user->setDispensary($dispensary);
            $this->saveOrUpdateUserObject($office, $userGroups, $userManager, $user, $name);
        }
    }
}
