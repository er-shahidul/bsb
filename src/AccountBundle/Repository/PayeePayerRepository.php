<?php

namespace AccountBundle\Repository;

use AccountBundle\Entity\PayeePayer;

/**
 * PayeeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PayeePayerRepository extends BaseAccountsRepository
{
    public function getDropDownOption($fundType)
    {
        /** Default Option */
        $data = ['toOrFrom' => [], 'against' => []];

        if (!$fundType) return $data;

        /** @var PayeePayer $payer */
        foreach ($this->findBy(['fundType' => $fundType]) as $payer) {
            $data[$payer->getUseFor()][$payer->getName()] = $payer->getName();
        }
        return $data;
    }
}