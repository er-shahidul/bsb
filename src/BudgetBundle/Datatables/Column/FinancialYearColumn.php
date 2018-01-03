<?php

namespace BudgetBundle\Datatables\Column;

use AppBundle\Entity\FinancialYear;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Data\DatatableQuery;

class FinancialYearColumn extends Column
{
    public function renderSingleField(array &$row)
    {
        $row['financialYear']['id'] = FinancialYear::getFinancialLabel($row['financialYear']['id']);
    }
}
