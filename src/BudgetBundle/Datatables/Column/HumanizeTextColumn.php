<?php

namespace BudgetBundle\Datatables\Column;

use Sg\DatatablesBundle\Datatable\Column\AbstractColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Helper;

class HumanizeTextColumn extends VirtualColumn
{
    public function renderSingleField(array &$row)
    {
        $path = Helper::getDataPropertyPath($this->data);
        $value = $row[$this->getSearchColumn()];

        if (!empty($value)) {
            $value = sprintf('<span class="label label-%s"> %s </span>', $this->colorClass($value), self::humanize($value));
        }
        $this->accessor->setValue($row, $path, $value);
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
