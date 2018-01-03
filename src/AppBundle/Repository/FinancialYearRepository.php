<?php

namespace AppBundle\Repository;

use AppBundle\Entity\FinancialYear;
use AppBundle\Utility\DateUtil;
use Doctrine\ORM\EntityRepository;

class FinancialYearRepository extends EntityRepository
{
    public function isCurrentFinancialYearClosed()
    {

    }

    /**
     * @return FinancialYear|object
     */
    public function getActiveFinancialYear()
    {
        return $this->find(DateUtil::getCurrentFinancialYear());
    }

}