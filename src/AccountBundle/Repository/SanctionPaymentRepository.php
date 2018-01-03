<?php

namespace AccountBundle\Repository;

use AccountBundle\Entity\FundHead;
use AccountBundle\Entity\FundType;
use AccountBundle\Entity\SanctionPayment;

/**
 * SanctionPaymentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SanctionPaymentRepository extends SanctionEntryRepository
{
    /**
     * @param FundType $fundType
     * @return array
     */
    public function noteSheetByFundType(FundType $fundType)
    {
        return $this->findBy(['fundType' => $fundType]);
    }

    public function noteSheetByFundTypeArray(FundType $fundType)
    {
        $data = [];

        /** @var SanctionPayment $value */
        foreach ($this->noteSheetByFundType($fundType) as $value) {
            $data[$value->getId()] = $value->getNoteSheetNumber();
        }

        return $data;
    }
}
