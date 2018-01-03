<?php

namespace AppBundle\Command;

use AppBundle\Entity\FinancialYear;
use AppBundle\Entity\Office;
use BudgetBundle\Entity\Budget;
use BudgetBundle\Entity\BudgetDetail;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use UserBundle\Entity\User;

class DemoBudgetCommand extends ContainerAwareCommand
{
    /** @var  EntityManager */
    protected $em;

    protected function configure()
    {
        $this->setName('basb:demo:budget-populate')
            ->addArgument('year', InputArgument::REQUIRED, 'Budget Year')
            ->setDescription('Populate budget data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $currentYear = $input->getArgument('year');
        $financialYear = $this->em->getRepository('AppBundle:FinancialYear')->find($currentYear);

        if (!$financialYear) {
            $financialYear = new FinancialYear($currentYear);
            $this->em->persist($financialYear);
        }

        $groupList =array(
            'HQ' => array(
                "Budget Clerk" => array('ROLE_BUDGET_CLERK'),
                "Account Clerk" => array('ROLE_ACCOUNT_CLERK'),
                "Welfare Clerk" => array('ROLE_WELFARE_CLERK'),
                "Medicine Clerk" => array('ROLE_MEDICINE_CLERK'),
                "Head Clerk" => array('ROLE_HEAD_CLERK'),
                "AO" => array('ROLE_AO'),
                "DD" => array('ROLE_DD'),
                "Director" => array('ROLE_DIRECTOR'),
                "Board Member" => array('ROLE_BOARD_MEMBER'),
                "Office Admin" => array('ROLE_OFFICE_ADMIN')
            ),
            'DASB' => array(
                "DASB Clerk" => array('ROLE_DASB_CLERK'),
                "IO" => array('ROLE_IO'),
                "Secretary" => array('ROLE_SECRETARY'),
                "Office Admin" => array('ROLE_OFFICE_ADMIN')
            )
        );

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

            $budget = $this->em->getRepository('BudgetBundle:Budget')->findOneBy(['financialYear' => $financialYear, 'office' => $office]);

            if ($budget) {
                $output->writeln(
                    '<error>Skip budget create for office ' . $office->getName() . ' of year ' . $currentYear . '</error>'
                );
                continue;
            }

            $this->initBudget($financialYear, $office, 'new');

            $output->writeln('<info>Budget Created Successfully for : ' . $office->getName() . ' of year ' . $currentYear . '</info>');
        }
        $this->em->flush();
    }

    /**
     * @param $financialYear
     *
     * @return \BudgetBundle\Entity\Budget
     */
    public function initBudget($financialYear, $office, $type)
    {
        $budget = new Budget();
        $budget->setFinancialYear($financialYear);
        $budget->setOffice($office);
        $this->em->persist($budget);

        $budgetHeads = $this->em->getRepository('BudgetBundle:BudgetHead')->getChildBudgetHead();

        foreach ($budgetHeads as $budgetHead) {
            $budgetDetail = new BudgetDetail();

            $budgetDetail->setBudgetHead($budgetHead);
            $budgetDetail->setBudget($budget);
            $budgetDetail->setRequestAmount(random_int(0, 100));
            $this->em->persist($budgetDetail);
        }

        return $budget;
    }

    protected function createUser(UserManager $userManager, $userName)
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

    protected function getOffices()
    {
        return $this->em->getRepository('AppBundle:Office')->findAll();
    }

    /**
     * @param Office $office
     * @param $userGroups
     * @param UserManager $userManager
     */
    protected function createUserForOffice(Office $office, $userGroups, UserManager $userManager)
    {
        foreach ($userGroups[$office->getOfficeType()->getName()] as $name => $group) {
            $userName = strtolower(str_replace(' ', '_', $office->getName() . "_" . $name));
            /** @var User $user */
            $user = $userManager->findUserByUsername($userName);

            if ($user == NULL) {
                $user = $this->createUser($userManager, $userName);
            }
            $user->setOffice($office);
            $user->addGroup($userGroups[$office->getOfficeType()->getName()][$name]);
            $userManager->updateUser($user);
        }
    }
}
