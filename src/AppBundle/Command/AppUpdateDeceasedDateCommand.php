<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;

class AppUpdateDeceasedDateCommand extends ContainerAwareCommand
{
    use BaseMigrationCommand;

    const IMPORT_FILE_HEADER_HASH = "2e82d731d4030d365531d2deb06164b3";

    protected function configure()
    {
        $this
            ->setName('app:ex:update-deceased-date')
            ->setDescription('Update deceased date of Ex-Serviceman from a csv file')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to csv file')
            ->setHelp(<<<EOT
<info>The provided CSV should have only two column like </info> 

+============+==============+
| PersonalNo | DeceasedDate |
+============+==============+
|      00000 | m/d/Y        |
+------------+--------------+

EOT
            )
        ;
    }

    function process(array $line, int $lineNumber)
    {
        $this->updateDeceasedDate($line);
    }

    private function updateDeceasedDate($serviceman)
    {
        list($personnelNumber, $deceasedDate) = $serviceman;

        $exServiceman = $this->getEntityManager()->getRepository('PersonnelBundle:ExServiceman')->findOneBy(['identityNumber' => $personnelNumber]);

        if ($exServiceman === NULL) {
            return;
        }

        $exServiceman->setDeceasedDate($this->normalizeDate($deceasedDate));
        $exServiceman->setDeceased(TRUE);

        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
        gc_collect_cycles();
    }

    /**
     * @param array $heading
     *
     * @return bool
     */
    protected function isInValidHeading(array $heading)
    {
        return count($heading) != 2 || md5(implode($heading)) !== self::IMPORT_FILE_HEADER_HASH;
    }

    /**
     * @param $line
     *
     * @return bool
     */
    protected function isInValidDataSet($line): bool
    {
        return count($line) != 2;
    }
}
