<?php
namespace AppBundle\Datatables\Column;

use Sg\DatatablesBundle\Datatable\Action\Action;

use Sg\DatatablesBundle\Datatable\Column\AbstractColumn;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\ColumnInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class ActionColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ActionDropDownColumn extends ActionColumn
{
    public function getCellContentTemplate()
    {
        return 'AppBundle:_template/ActionDropDown:action_column.html.twig';
    }
}
