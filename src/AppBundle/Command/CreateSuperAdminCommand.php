<?php

namespace AppBundle\Command;

use FOS\UserBundle\Model\UserManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use UserBundle\Entity\User;

class CreateSuperAdminCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('basb:populate:super-admin')
            ->setDescription('Populate BASB Core User account.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $created = TRUE;
        /** @var UserManager $userManager */
        $userManager = $this->getContainer()->get('fos_user.user_manager');

        $userName = $this->getNonEmptyInput($input, $output, 'Enter user name for super admin:');
        /** @var User $user */
        $user = $userManager->findUserByUsername($userName);

        if ($user != NULL) {

            $created = FALSE;
            $output->writeln('<info>User admin already exist.</info>');
            $password = trim($this->getNewPassword($output));

            if (!empty($password)) {
                $user->setPlainPassword($password);
            }

        } else {
            $user = $this->createUser($userManager, $input, $output, $userName);
        }

        $user->addGroup($this->getSuperAdminUserGroup());

        $userManager->updateUser($user);

        if ($created) {
            $output->writeln('<info>User has been created successfully.</info>');
        }
    }

    protected function createUser(UserManager $userManager, InputInterface $input, OutputInterface $output, $userName)
    {
        $output->writeln('<info>Populating User.</info>');

        $email = $this->getNonEmptyInput($input, $output, 'Please choose an email:');
        $password = $this->getNonEmptyInput($input, $output, 'Please choose a password:');

        $user = $userManager->createUser();

        $user->setUsername($userName);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled(TRUE);

        return $user;
    }

    protected function getSuperAdminUserGroup()
    {
        return $this->getUserGroup('Super Administrator', array('ROLE_SUPER_ADMIN'));;
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

    /**
     * @param OutputInterface $output
     *
     * @return mixed
     */
    protected function getNewPassword(OutputInterface $output)
    {
        return $this->getHelper('dialog')->ask(
            $output,
            'Enter new password(leave it blank to Keep old one):',
            ''
        );
    }

    /**
     * @param OutputInterface $output
     * @param                 $msg
     *
     * @return mixed
     */
    protected function getNonEmptyInput(InputInterface $input, OutputInterface $output, $msg)
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new Question($msg);

        $answer = $helper->ask($input, $output, $question);

        return $answer;
    }
}
