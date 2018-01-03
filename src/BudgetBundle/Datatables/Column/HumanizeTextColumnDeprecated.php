<?php

namespace BudgetBundle\Datatables\Column;

use Sg\DatatablesBundle\Datatable\Column\Column;

/**
 * Class HumanizeTextColumnDeprecated
 *
 * @deprecated deprecated in favour of BudgetBundle\Datatables\Column\HumanizeTextColumn instead
 *
 * @package BudgetBundle\Datatables\Column
 */
class HumanizeTextColumnDeprecated extends Column
{
    public function renderSingleField(array &$row)
    {
        if (isset($row['budgetStatus'])) {
            $row['budgetStatus'] = sprintf('<span class="label label-%s"> %s </span>', $this->colorClass($row['budgetStatus']), self::humanize($row['budgetStatus']));;
        }

        if (isset($row['status'])) {
            $row['status'] = sprintf('<span class="label label-%s"> %s </span>', $this->colorClass($row['status']), self::humanize($row['status']));;
        }

        if (isset($row['amendmentStatus'])) {
            $row['amendmentStatus'] = str_replace('amendment', 'amendment_', $row['amendmentStatus']);
            $row['amendmentStatus'] = sprintf('<span class="label label-%s"> %s </span>', $this->colorClass($row['amendmentStatus']), self::humanize($row['amendmentStatus']));;
        }
    }

    static public function humanize($value)
    {
        return ucwords(trim(strtolower(preg_replace(array('/([A-Z])/', '/[_\s]+/'), array('_$1', ' '), $value))));
    }

    static public function reformStatusValue($string)
    {
        return strtolower(str_replace(' ', '_', trim(strip_tags($string))));
    }

    protected function colorClass($status)
    {
        $text = 'warning';
        switch ($status) {
            case 'draft': $text = 'default'; break;
            case 'approved': $text = 'success'; break;
        }

        return $text;
    }

    /**
     * Render toMany.
     *
     * @param array $row
     *
     * @return $this
     */
    public function renderToMany(array &$row)
    {
        // TODO: Implement renderToMany() method.
    }

    /**
     * Get the template for the 'renderCellContent' function.
     *
     * @return string
     */
    public function getCellContentTemplate()
    {
        // TODO: Implement getCellContentTemplate() method.
    }
}
