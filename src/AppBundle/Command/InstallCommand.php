<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('basb:install')
             ->setDescription('BASB installer.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Installing Application.</info>');
        $output->writeln('');

        $this->setupStep($input, $output);

        $output->writeln('<info>BASB application has been successfully installed.</info>');
    }

    protected function setupStep(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Setting up database.</info>');

        $this->runCommand('doctrine:database:create', $input, $output);
        $this->runCommand('doctrine:schema:create', $input, $output);

        $output->writeln('');
        $output->writeln('<info>Load Master Data.</info>');
        $this->runCommand('basb:populate:master-data', $input, $output);

        $output->writeln('');
        $output->writeln('<info>Administration setup.</info>');
        $this->runCommand('basb:populate:groups', $input, $output);
        $this->runCommand('basb:populate:super-admin', $input, $output);

        return $this;
    }

    private function runCommand($command, InputInterface $input, OutputInterface $output)
    {
        $this->getApplication()
             ->find($command)
             ->run($input, $output);

        return $this;
    }
}
