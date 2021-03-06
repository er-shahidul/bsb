<?php

namespace AccountBundle\Repository;

use AccountBundle\Entity\FundType;

/**
 * FundTypeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FundTypeRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAll()
    {
        return $this->findBy([], ['sort' => 'asc']);
    }

    public function getOfficeFundType($isDasb)
    {
        return $isDasb ? $this->findBy(['basbFund' => false], ['sort' => 'asc']) : $this->findAll();
    }

    public function getOfficeFundTypeAsArray($isDasb)
    {
        $fundTypes = $this->getOfficeFundType($isDasb);
        $data = [];
        foreach ($fundTypes as $fundType) {
            $data[$fundType->getName()] = $fundType->getId();
        }

        return $data;
    }

    /**
     * @param $fundTypeName
     * @return FundType|object
     */
    public function getFundTypeByString($fundTypeName)
    {
        $typeMapping = [
            'bscr' => 1,
            'rcel' => 2,
            'other' => 9
        ];

        return $this->find($typeMapping[strtolower($fundTypeName)]);
    }
}
