<?php

namespace BudgetBundle\Twig;

use AppBundle\Entity\FinancialYear;

class FinancialYearLabel extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('financialYearLabel', [$this, 'financialYearLabel']),
        ];
    }

    function financialYearLabel($year, $prefix = false) {
        return FinancialYear::getFinancialLabel($year, $prefix);
    }
}
