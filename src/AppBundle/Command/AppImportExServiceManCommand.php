<?php

namespace AppBundle\Command;

use PersonnelBundle\Entity\Corp;
use PersonnelBundle\Entity\District;
use PersonnelBundle\Entity\ExServiceman;
use PersonnelBundle\Entity\Gender;
use PersonnelBundle\Entity\Rank;
use PersonnelBundle\Entity\RetirementReason;
use PersonnelBundle\Entity\Service;
use PersonnelBundle\Entity\Upazila;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class AppImportExServiceManCommand extends ContainerAwareCommand
{
    use BaseMigrationCommand;

    const IMPORT_FILE_HEADER_HASH = "130dea3b16fb073e7d12f1dee69f090a";

    /** @var  array */
    private $data = [];
    private $notFound = [];

    /** @var  PropertyAccessor */
    private $propertyAccessor;

    protected function configure()
    {
        $this
            ->setName('app:import:ex-service-man')
            ->setDescription('Import Ex-Serviceman information from a csv file')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to csv file')
            ->setHelp(<<<EOT
<info>Use following query to generate the CSV file from old database</info> 

SELECT
  em.perID ID,
  srv.Service,
  corps.Corp,
  em.PersonalNo,
  ranks.Rank,
  em.Trade,
  em.Name,
  em.IdentificationMark,
  em.DOB,
  em.DOEnroll,
  em.DORetd,
  retirements.Retirements,
  em.LastUnitServ,
  homeDistricts.Districts homeDistrict,
  em.FathersName,
  em.Address1 Address,
  upazilas.Upazilla,
  districts.Districts,
  em.Status
FROM
 tblmain em
  LEFT JOIN tblservice srv USING (ServiceID)
  LEFT JOIN tblcorps corps USING (CorpsID)
  LEFT JOIN tblrank ranks USING (RankID)
  LEFT JOIN tbldistricts homeDistricts ON em.HomeDistrict = homeDistricts.DistrictsID
  LEFT JOIN tbldistricts districts ON em.DistrictID = districts.DistrictsID
  LEFT JOIN tblupazilla upazilas ON em.UpazillaID = upazilas.UpazillaID
LEFT JOIN tblretirements retirements ON em.RetdID = retirements.RetirementsID

EOT
);
    }

    private function populateMasterDataCache()
    {
        if ($this->propertyAccessor !== NULL) {
            return;
        }

        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();

        $cachedEntities = [
            Corp::class     => ['service.id', 'name'],
            Rank::class     => ['service.id', 'short'],
            District::class => 'name',
            Upazila::class  => ['district.id', 'name'],
        ];

        foreach ($cachedEntities as $entity => $key) {
            $this->data[$entity] = $this->indexBy($key, $this->getEntityManager()->getRepository($entity)->findAll());
        }
    }


    private function getReference($key, $entity)
    {
        if (isset($this->data[$entity][$key])) {
            return $this->getEntityManager()->getReference($entity, $this->data[$entity][$key]);
        }

        $this->notFound[$entity][$key] = TRUE;

        return NULL;
    }

    function process(array $line, int $lineNumber)
    {
        if ($lineNumber < 2) {
            $this->populateMasterDataCache();
        }

        $this->importServiceMan(array_map('trim', $line));
    }

    private function importServiceMan(array $serviceman)
    {
        list(
            ,                       //0
            $service,               //1
            $corp,                  //2
            $personnelNumber,       //3
            $rank,                  //4
            $trade,                 //5
            $name,                  //6
            $identificationMark,    //7
            $dob,                   //8
            $doe,                   //9
            $dor,                   //10
            $retirementReason,      //11
            $lastServedUnit,        //12
            $homeDistrict,          //13
            $fatherName,            //14
            $address,               //15
            $upazila,               //16
            $district,              //17
            $status                 //18
            ) = $serviceman;

        $exServiceman = $this->getEntityManager()->getRepository('PersonnelBundle:ExServiceman')->findOneBy(['identityNumber' => $personnelNumber]);

        if ($exServiceman === NULL) {
            $exServiceman = new ExServiceman();
            $exServiceman->setIdentityNumber($personnelNumber);

        } else {
            $this->getEntityManager()->clear();

            return;
        }

        $exServiceman->setService($this->normalizeService($service));

        if ($exServiceman->getService() !== NULL) {
            $exServiceman->setCorp($this->normalizeCorp($exServiceman->getService()->getId() . $corp));
            $exServiceman->setRank($this->normalizeRank($exServiceman->getService()->getId() . $rank));
        }

        $exServiceman->setTrade($trade);
        $exServiceman->setName($name);
        $exServiceman->setIdentificationMark($identificationMark);
        $exServiceman->setDateOfBirth($this->normalizeDate($dob));
        $exServiceman->setJoiningDate($this->normalizeDate($doe));
        $exServiceman->setRetirementDate($this->normalizeDate($dor));
        $exServiceman->setRetirementReason($this->normalizeRetirementReason($retirementReason));
        $exServiceman->setLastServedUnit($lastServedUnit);
        $exServiceman->setPermanentDistrict($this->normalizeDistrict($homeDistrict));
        $exServiceman->setDistrict($this->normalizeDistrict($district));

        if ($exServiceman->getDistrict() !== NULL) {
            $exServiceman->setUpazila($this->normalizeUpazila($exServiceman->getDistrict()->getId(), $upazila));
        }

        $exServiceman->setFathersName($fatherName);

        if ($status === "0") {
            $exServiceman->setDeceased(TRUE);
        }

        $exServiceman->setPermanentPostOffice('');
        $exServiceman->setPermanentVillage('');

        list($village, $postOffice) = $this->parseAddress($address);
        $exServiceman->setPostOffice($postOffice);
        $exServiceman->setVillage($village);

        $exServiceman->setGender($this->getEntityReference('Male', Gender::class));

        $this->getEntityManager()->persist($exServiceman);
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
        gc_collect_cycles();
    }

    private function indexBy($key, $items)
    {
        $data = [];
        foreach ($items as $item) {
            try {
                $data[$this->getKeyValue($key, $item)] = $item->getId();
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
        }

        return $data;
    }

    /**
     * @param $key
     * @param $item
     *
     * @return mixed
     */
    private function getKeyValue($key, $item)
    {
        if (is_array($key)) {
            $str = '';
            foreach ($key as $keyItem) {
                $str .= $this->propertyAccessor->getValue($item, $keyItem);
            }

            return $str;
        }

        return $this->propertyAccessor->getValue($item, $key);
    }

    /**
     * @param $service
     *
     * @return Service|Object
     */
    private function normalizeService($service)
    {
        if (empty($service)) {
            return NULL;
        }

        $service = $service === 'Expired British Soldiers' ? 'Ex British' : $service;

        return $this->getEntityManager()->getReference(Service::class, $service);
    }

    /**
     * @param $corp
     *
     * @return Corp|Object
     */
    private function normalizeCorp($corp)
    {
        if (empty($corp)) {
            return NULL;
        }

        return $this->getReference($corp, Corp::class);
    }

    /**
     * @param $rank
     *
     * @return Rank|Object
     */
    private function normalizeRank($rank)
    {
        if (empty($rank)) {
            return NULL;
        }

        return $this->getReference($rank, Rank::class);
    }

    /**
     * @param $retirementReason
     *
     * @return RetirementReason|Object
     */
    private function normalizeRetirementReason($retirementReason)
    {
        if (empty($retirementReason)) {
            return NULL;
        }

        return $this->getEntityManager()->getReference(RetirementReason::class, $retirementReason);
    }

    /**
     * @param $homeDistrict
     *
     * @return null|object|District
     */
    private function normalizeDistrict($homeDistrict)
    {
        return $this->getReference($homeDistrict, District::class);
    }

    private function normalizeUpazila($getId, $upazila)
    {
        return $this->getReference($getId . $upazila, Upazila::class);
    }

    /**
     * @param $id
     * @param $class
     *
     * @return object
     */
    private function getEntityReference($id, $class)
    {
        return $this->getEntityManager()->getReference($class, $id);
    }

    private function parseAddress($address)
    {
        $addressParts = ['', ''];

        if (empty($address)) {
            return $addressParts;
        }

        $_parts = explode(',', $address);

        foreach ($_parts as $part) {
            $items = array_map('trim', explode(':', $part));
            switch (strtolower($items[0])) {
                case 'vill':
                    if (isset($items[1])) {
                        $addressParts[0] = $items[1];
                    }
                    break;
                case 'post':
                    if (isset($items[1])) {
                        $addressParts[1] = $items[1];
                    }

                    break;
            }
        }

        return $addressParts;
    }

    /**
     * @param $heading
     *
     * @return bool
     */
    protected function isInValidHeading($heading): bool
    {
        return count($heading) != 19 || md5(implode($heading)) !== self::IMPORT_FILE_HEADER_HASH;
    }

    /**
     * @param $line
     *
     * @return bool
     */
    protected function isInValidDataSet($line): bool
    {
        return count($line) != 19;
    }
}
