<?php

namespace AppBundle\Command;

use AccountBundle\Entity\FundHead;
use AccountBundle\Entity\FundType;
use AccountBundle\Entity\Payee;
use AccountBundle\Entity\Payer;
use AppBundle\Entity\SortableMasterData;
use AppBundle\Entity\Office;
use AppBundle\Entity\OfficeType;
use BudgetBundle\Entity\BudgetHead;
use BudgetBundle\Entity\BudgetIncomeHead;
use Doctrine\ORM\EntityManager;
use MedicalBundle\Entity\Dispensary;
use MedicalBundle\Entity\Medicine;
use PersonnelBundle\Entity\BloodGroup;
use PersonnelBundle\Entity\Corp;
use PersonnelBundle\Entity\District;
use PersonnelBundle\Entity\Gender;
use PersonnelBundle\Entity\Rank;
use PersonnelBundle\Entity\RelationType;
use PersonnelBundle\Entity\Religion;
use PersonnelBundle\Entity\RetirementReason;
use PersonnelBundle\Entity\Service;
use PersonnelBundle\Entity\ServingType;
use PersonnelBundle\Entity\Upazila;
use PersonnelBundle\Entity\WelfareFund;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WelfareBundle\Entity\MicroCreditProjectType;
use WelfareBundle\Entity\SKSApplicationType;

class PopulateMasterDataCommand extends ContainerAwareCommand
{
    /** @var  EntityManager */
    protected $em;

    protected function configure()
    {
        $this->setName('basb:populate:master-data')
            ->setDefinition([
                new InputOption('only', '', InputOption::VALUE_OPTIONAL, 'Populate partial data. Comma separated type list(blood/budget_head/corp/district/fund/fund_head/income_head/gender/office/rank/relation/religion/retirement/service/upazila/dispensary/medicine) to import', "")
            ])
            ->setHelp(<<<EOT
The <info>basb:populate:master-data</info> command helps you Populate Master data for BASB Automation System.

To populate all master data run 
<info>php app/console basb:populate:master-data</info>

If you want to populate partial data you can provide comma separated item in --only parameter.
<info>php app/console basb:populate:master-data --only=district,fund</info>
EOT
            )
            ->setDescription('Populate Master data for BASB Automation System');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $items = trim($input->getOption('only'));

        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        if ($items === "") {
            $this->populateAll($output);
        } else {
            $items = array_map('trim', explode(',', $items));
            $this->populatePartial($output, $items);
        }

        $output->writeln('<info>All Master data has been populated successfully.</info>');
    }

    /**
     * @param OutputInterface $output
     */
    protected function populateAll(OutputInterface $output)
    {
        //blood/budget_head/corp/district/fund/fund_head/gender/office/rank/relation/religion/retirement/service/upazila
        $this->populateOffice($output);
        $this->populateDispensary($output);
        $this->populateMedicine($output);
        $this->populateBudgetHead($output);
        $this->populateIncomeHead($output);

        $this->populateService($output);
        $this->populateCorp($output);
        $this->populateRank($output);

        $this->populateBlood($output);
        $this->populateFund($output);
        $this->populateFundHead($output);
        $this->populateGender($output);
        $this->populateRelation($output);
        $this->populateServingType($output);
        $this->populateReligion($output);
        $this->populateRetirementReasons($output);

        $this->populateDistrict($output);
        $this->populateUpazila($output);

        $this->populateSKSApplicationType($output);
        $this->populateMicroCreditProjectType($output);

        $this->populatePayeeAndPayer($output);
    }

    /**
     * @param OutputInterface $output
     */
    protected function populatePartial(OutputInterface $output, $items = array())
    {

//blood/budget_head/corp/district/fund/fund_head/gender/office/rank/relation/religion/retirement/service/upazila

        if (in_array('office', $items)) {
            $this->populateOffice($output);
        }

        if (in_array('dispensary', $items)) {
            $this->populateDispensary($output);
        }

        if (in_array('medicine', $items)) {
            $this->populateMedicine($output);
        }

        if (in_array('budget_head', $items)) {
            $this->populateBudgetHead($output);
        }

        if (in_array('income_head', $items)) {
            $this->populateIncomeHead($output);
        }

        if (in_array('service', $items) || in_array('corp', $items) || in_array('rank', $items)) {
            $this->populateService($output);
        }

        if (in_array('corp', $items)) {
            $this->populateCorp($output);
        }

        if (in_array('rank', $items)) {
            $this->populateRank($output);
        }

        if (in_array('blood', $items)) {
            $this->populateBlood($output);
        }

        if (in_array('fund', $items) || in_array('fund_head', $items)) {
            $this->populateFund($output);
        }

        if (in_array('fund_head', $items)) {
            $this->populateFundHead($output);
        }

        if (in_array('gender', $items)) {
            $this->populateGender($output);
        }

        if (in_array('relation', $items)) {
            $this->populateRelation($output);
        }

        if (in_array('religion', $items)) {
            $this->populateReligion($output);
        }

        if (in_array('retirement', $items)) {
            $this->populateRetirementReasons($output);
        }

        if (in_array('district', $items)) {
            $this->populateDistrict($output);
        }

        if (in_array('upazila', $items)) {
            $this->populateUpazila($output);
        }

        if (in_array('sks_application_type', $items)) {
            $this->populateSKSApplicationType($output);
        }

        if (in_array('micro_credit_project_type', $items)) {
            $this->populateMicroCreditProjectType($output);
        }

        if (in_array('payee', $items)) {
            $this->populatePayeeAndPayer($output);
        }
    }

    protected function populateService(OutputInterface $output)
    {
        $this->populateMasterData('service', Service::class);
        $output->writeln('<info>Service data has been populated successfully.</info>');
    }

    protected function populateGender(OutputInterface $output)
    {
        $this->populateMasterData('gender', Gender::class);
        $output->writeln('<info>Gender data has been populated successfully.</info>');
    }

    protected function populateRetirementReasons(OutputInterface $output)
    {
        $this->populateMasterData('retirement', RetirementReason::class);
        $output->writeln('<info>RetirementReason data has been populated successfully.</info>');
    }

    private function populateBlood(OutputInterface $output)
    {
        $this->populateMasterData('blood', BloodGroup::class);
        $output->writeln('<info>Service data has been populated successfully.</info>');
    }

    private function populateFund(OutputInterface $output)
    {
        $this->populateMasterData('wf_fund', WelfareFund::class);
        $this->populateAccountFund();

        $output->writeln('<info>Fund data has been populated successfully.</info>');
    }

    private function populateRelation(OutputInterface $output)
    {
        $this->populateMasterData('relation', RelationType::class);
        $output->writeln('<info>Relation data has been populated successfully.</info>');
    }

    private function populateReligion(OutputInterface $output)
    {
        $this->populateMasterData('religion', Religion::class);
        $output->writeln('<info>Religion data has been populated successfully.</info>');
    }

    private function populateServingType(OutputInterface $output)
    {
        $this->populateMasterData('serving_type', ServingType::class);
        $output->writeln('<info>Relation data has been populated successfully.</info>');
    }

    protected function populateCorp(OutputInterface $output)
    {
        $data = $this->loadCSV('corp');
        $repository = $this->em->getRepository(Corp::class);

        foreach ($data as $key => $row) {
            if ($key === 0) continue;

            $service = $this->em->getRepository('PersonnelBundle:Service')->find($row[1]);
            if (!$service) continue;
            if ($repository->findOneBy(['name' => $row[0], 'service' => $service]) !== NULL) continue;

            $rank = new Corp();
            $rank->setName($row[0]);
            $rank->setService($service);
            $this->em->persist($rank);
        }

        $this->em->flush();
        $this->em->clear();
        $output->writeln('<info>Corp data has been populated successfully.</info>');
    }

    private function populateFundHead(OutputInterface $output)
    {
        $this->populateFundHeadByOfficeType('basb_fund_head', $this->createOrGetOfficeType('HQ'));
        $this->populateFundHeadByOfficeType('dasb_fund_head', $this->createOrGetOfficeType('DASB'));

        $output->writeln('<info>Fund head data has been populated successfully.</info>');
    }

    private function populateIncomeHead(OutputInterface $output)
    {
        $data = $this->loadCSV('income_head');
        $repository = $this->em->getRepository('BudgetBundle:BudgetIncomeHead');

        $this->populateInBudgetHeadData($data, $repository, BudgetIncomeHead::class);

        $output->writeln('<info>Budget Income Head data has been populated successfully.</info>');
    }

    private function populateRank(OutputInterface $output)
    {
        $data = $this->loadCSV('rank');
        $repository = $this->em->getRepository(Rank::class);

        foreach ($data as $key => $row) {
            if ($key === 0 || count($row) < 3) continue;

            $service = $this->em->getRepository('PersonnelBundle:Service')->find($row[2]);
            if (!$service) continue;

            if ($repository->findOneBy(['name' => $row[0], 'service' => $service]) !== NULL) continue;

            $rank = new Rank();
            $rank->setName($row[0]);
            $rank->setShort($row[1]);
            $rank->setService($service);
            $this->em->persist($rank);
        }

        $this->em->flush();
        $this->em->clear();

        $output->writeln('<info>Rank data has been populated successfully.</info>');

    }

    private function populateDistrict(OutputInterface $output)
    {
        $data = $this->loadCSV('district');
        $repository = $this->em->getRepository(District::class);

        foreach ($data as $key => $row) {
            if ($key === 0) continue;
            if ($repository->findOneBy(['name' => $row[0]]) !== NULL) continue;

            $district = new District();
            $district->setName($row[0]);
            $this->em->persist($district);
        }

        $this->em->flush();
        $this->em->clear();

        $output->writeln('<info>District data has been populated successfully.</info>');
    }

    private function populateUpazila(OutputInterface $output)
    {
        $data = $this->loadCSV('upazila');

        foreach ($data as $key => $row) {
            if ($key === 0) continue;

            $district = $this->em->getRepository('PersonnelBundle:District')->findOneBy(array('name' => $row[1]));
            if (!$district) continue;

            $upazila = new Upazila();
            $upazila->setName($row[0]);
            $upazila->setDistrict($district);
            $this->em->persist($upazila);
        }

        $this->em->flush();
        $this->em->clear();

        $output->writeln('<info>Upazila data has been populated successfully.</info>');
    }

    private function populateOffice(OutputInterface $output)
    {
        $officeType = [];

        $officeType['HQ'] = $this->createOrGetOfficeType('HQ');
        $officeType['DASB'] = $this->createOrGetOfficeType('DASB');

        $data = $this->loadCSV('office');
        $i = 0;
        foreach ($data as $key => $row) {
            if ($key === 0) continue;

            $type = $officeType[$row[1]];

            $office = $this->officeExists($row[0], $type);

            if (!$office) {
                $office = new Office();
                $office->setName($row[0]);
                $office->setOfficeType($type);
                $this->em->persist($office);
                $i++;
            }

            $office->setAddress(trim($row[2]));
            $office->setPhone(trim($row[3]));
            $office->setMobile(trim($row[4]));
            $office->setEmail(trim($row[5]));
            $office->setFax(trim($row[6]));
        }

        $this->em->flush();
        $this->em->clear();

        if ($i > 0) {
            $output->writeln('<info>(' . $i . ') New Office data has been populated successfully.</info>');
        } else {
            $output->writeln('<comment>No new Office data created.</comment>');
        }
    }

    private function populateDispensary(OutputInterface $output)
    {

        $type = $this->createOrGetOfficeType('DASB');

        $data = $this->loadCSV('dispensary');
        $i = 0;
        foreach ($data as $key => $row) {
            if ($key === 0) continue;

            $office = $this->officeExists($row[1], $type);

            if (!$office) {
                continue;
            }

            if ($this->dispensaryExists($row[0], $office)) {
                continue;
            }

            $dispensary = new Dispensary();

            $dispensary->setOffice($office);
            $dispensary->setName($row[0]);
            $this->em->persist($dispensary);
            $i++;
        }

        $this->em->flush();
        $this->em->clear();

        if ($i > 0) {
            $output->writeln('<info>(' . $i . ') New dispensary data has been populated successfully.</info>');
        } else {
            $output->writeln('<comment>No new dispensary data created.</comment>');
        }
    }

    private function populateMedicine(OutputInterface $output)
    {
        $data = $this->loadCSV('medicine');
        foreach ($data as $key => $row) {
            if ($key === 0) continue;

            $name = trim($row[0]);
            if ($this->em
                ->getRepository('MedicalBundle:Medicine')
                ->findOneBy(['name'   => $name])) {
                continue;
            }

            $medicine = new Medicine();

            $medicine->setName($name);
            $medicine->setAccountUnit($row[1]);
            $medicine->setSort($key);
            $this->em->persist($medicine);
        }

        $this->em->flush();
        $this->em->clear();

        $output->writeln('<info>Medicine data has been populated successfully.</info>');
    }

    private function populateBudgetHead(OutputInterface $output)
    {
        $data = $this->loadCSV('budget_head');
        $repository = $this->em->getRepository('BudgetBundle:BudgetHead');
        $this->populateInBudgetHeadData($data, $repository, BudgetHead::class);

        $output->writeln('<info>Budget Head data has been populated successfully.</info>');
    }

    private function populateMasterData($type, $entity)
    {
        $data = $this->loadCSV($type);
        $repository = $this->em->getRepository($entity);

        foreach ($data as $key => $row) {
            if ($key === 0) continue;
            if ($repository->find($row[0]) !== NULL) continue;

            $service = new $entity();

            if ($service instanceof SortableMasterData) {
                $service->setId($row[0]);
                $service->setSort($key);
                $this->em->persist($service);
            }
        }

        $this->em->flush();
        $this->em->clear();
    }

    private function populateSKSApplicationType(OutputInterface $output)
    {
        $this->populateMasterData('sks_application_type', SKSApplicationType::class);
        $output->writeln('<info>SKS Application Type data has been populated successfully.</info>');
    }

    private function populateMicroCreditProjectType(OutputInterface $output)
    {
        $this->populateMasterData('micro_credit_project_type', MicroCreditProjectType::class);
        $output->writeln('<info>Micro-credit project Type data has been populated successfully.</info>');
    }

    protected function loadCSV($type)
    {
        return array_map('str_getcsv', file(__DIR__ . '/../DataFixtures/ORM/Data/' . $type . ".csv"));
    }

    private function populateAccountFund()
    {
        $data = $this->loadCSV('account_fund');
        $repository = $this->em->getRepository(FundType::class);

        foreach ($data as $key => $row) {
            if ($key === 0) continue;
            if ($repository->findOneBy(['name' => $row[0]]) !== NULL) continue;

            $fundType = new FundType();
            $fundType->setName($row[0]);
            $fundType->setSort($key);
            $fundType->setBasbFund(boolval($row[1]));
            $this->em->persist($fundType);
            $this->em->flush();
        }
    }

    private function populatePayeeAndPayer(OutputInterface $output)
    {
        $data = $this->loadCSV('account_payee');
        $fundTypes = $this->getFundTypes();

        foreach ($data as $key => $row) {
            if ($key === 0) continue;
            if (!array_key_exists($row[1], $fundTypes)) continue;

            $payee = new Payee();
            $payee->setName($row[0]);
            $payee->setFundType($fundTypes[$row[1]]);
            $payee->setUseFor($row[2]);
            $this->em->persist($payee);
            $this->em->flush();
        }

        $output->writeln('<info>Payee data has been populated successfully.</info>');

        $data = $this->loadCSV('account_payer');
        $fundTypes = $this->getFundTypes();

        foreach ($data as $key => $row) {
            if ($key === 0) continue;
            if (!array_key_exists($row[1], $fundTypes)) continue;

            $payee = new Payer();
            $payee->setName($row[0]);
            $payee->setFundType($fundTypes[$row[1]]);
            $payee->setUseFor($row[2]);
            $this->em->persist($payee);
            $this->em->flush();
        }

        $output->writeln('<info>Payer data has been populated successfully.</info>');
    }

    /**
     * @param $name
     *
     * @return OfficeType
     */
    private function createOrGetOfficeType($name)
    {
        $officeType = $this->em->getRepository(OfficeType::class)->findOneBy(['name' => $name]);
        if ($officeType !== NULL) {
            return $officeType;
        }

        $officeType = (new OfficeType())->setName($name);
        $this->em->persist($officeType);
        $this->em->flush($officeType);

        return $officeType;
    }

    /**
     * @param $fileType
     * @param $OfficeType
     */
    private function populateFundHeadByOfficeType($fileType, OfficeType $OfficeType)
    {
        $data = $this->loadCSV($fileType);
        foreach ($data as $key => $row) {
            if ($key === 0) continue;

            $type = $this->em->getRepository('AccountBundle:FundType')->findOneBy([
                'name' => $row[1]
            ]);

            if (!$type) continue;

            $fundHead = new FundHead();
            $fundHead->setName($row[0]);
            $fundHead->setFundType($type);
            $fundHead->setOfficeType($OfficeType);
            $fundHead->setSort($key);
            $this->em->persist($fundHead);
        }

        $this->em->flush();
        $this->em->clear();
    }

    /**
     * @param $name
     * @param $type
     *
     * @return Office|null|object
     */
    private function officeExists($name, $type)
    {
        return $this->em
            ->getRepository('AppBundle:Office')
            ->findOneBy(['name'       => $name,
                         'officeType' => $type
            ]);
    }

    /**
     * @param $data
     * @param $repository
     */
    private function populateInBudgetHeadData($data, $repository, $bhClass)
    {
        foreach ($data as $key => $row) {
            if ($key === 0) continue;

            if ($repository->findOneBy(array('code' => $row[0])) !== NULL) continue;

            /** @var BudgetHead $bh */
            $bh = new $bhClass();
            $bh->setCode($row[0]);
            $bh->setTitleEn($row[1]);
            $bh->setTitleBn($row[1]);
            $bh->setSort($key);

            if (!empty($row[2])) {
                $parentHead = $repository->findOneBy(array('code' => $row[2]));
                $bh->setParent($parentHead);
            }

            $this->em->persist($bh);
            $this->em->flush();
        }

        $this->em->clear();
    }

    private function getFundTypes()
    {
        $data = [];
        foreach ($this->em->getRepository('AccountBundle:FundType')->findAll() as $fundType) {
            $data[$fundType->getName()] = $fundType;
        }

        return $data;

    }

    /**
     * @param $name
     * @param $office
     *
     * @return \MedicalBundle\Entity\Dispensary|null|object
     */
    private function dispensaryExists($name, $office)
    {
        return $this->em
            ->getRepository('MedicalBundle:Dispensary')
            ->findOneBy(['name'   => trim($name),
                         'office' => $office
            ]);
    }
}