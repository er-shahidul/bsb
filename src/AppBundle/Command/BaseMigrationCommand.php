<?php

namespace AppBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

trait BaseMigrationCommand
{
    /**
     * @return ContainerInterface|null
     */
    abstract protected function getContainer();

    /**
     * @param array $heading
     *
     * @return bool
     */
    abstract protected function isInValidHeading(array $heading);

    /**
     * @param $line
     *
     * @return bool
     */
    abstract protected function isInValidDataSet($line): bool;

    /**
     * @param array $line
     * @param int   $lineNumber
     *
     * @return mixed
     */
    abstract function process(array $line, int $lineNumber);

    /** @var  EntityManagerInterface */
    private $em;

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function getEntityManager()
    {
        if (!$this->em) {
            $this->em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
            $this->em->getConnection()->getConfiguration()->setSQLLogger(NULL);
        }

        return $this->em;
    }

    /**
     * @param InputInterface $input
     *
     * @return \SplFileObject
     */
    protected function getFileObject(InputInterface $input)
    {
        $file = $input->getArgument('file');

        if (!file_exists(realpath($file))) {
            throw new FileNotFoundException($file . ' File Not found');
        }

        $rFile = new \SplFileObject($file, 'r');

        $rFile->setFlags(\SplFileObject::READ_CSV);

        return $rFile;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $this->getFileObject($input);

        $file->seek(PHP_INT_MAX);
        $max = $file->key() - 1;

        $file->rewind();

        $progress = new ProgressBar($output, $max);
        $progress->setFormat('verbose');
        $progress->setRedrawFrequency(50);


        foreach ($file as $line) {
            if ($file->key() == 0) {
                if ($this->isInValidHeading($line)) {
                    throw new InvalidArgumentException($file->getPath() . ' Invalid file');
                }
                continue;
            }

            if ($this->isInValidDataSet($line)) {
                continue;
            }

            $progress->advance();
            $this->process(array_map('trim', $line), $file->key());
        }

        echo PHP_EOL;

        $output->writeln('<info>Processing done</info>');
    }

    /**
     * @param        $dob
     *
     * @param string $format
     *
     * @return bool|\DateTime
     */
    protected function normalizeDate($dob, $format= 'm/d/Y')
    {
        $dateTime = \DateTime::createFromFormat($format, $dob);

        return $dateTime ? $dateTime : NULL;
    }

    protected function printError(OutputInterface $output, $msg)
    {
        $output->writeln("<error>$msg</error>");
    }
}