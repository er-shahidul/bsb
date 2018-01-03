<?php
namespace WelfareBundle\Datatables;
use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\MultiselectColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\ImageColumn;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Filter\NumberFilter;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;
use Sg\DatatablesBundle\Datatable\Editable\CombodateEditable;
use Sg\DatatablesBundle\Datatable\Editable\SelectEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextareaEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextEditable;
/**
 * Class WelfareApplicationDatatable
 *
 * @package WelfareBundle\Datatables
 */
class WelfareApplicationDatatable extends BaseDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($line){
            return $line;
        };
        return $formatter;
    }
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'dropdown';
        $this->features->set($this->getDefaultFeatures());
        $this->options->set($this->getDefaultOptions());
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('welfare_bscr_index')]));
        $this->setDefaultExportButtons([1,2,3]);
        $this->addActionButton('budget_expense_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);
        $this->columnBuilder
            ->add('status', Column::class, array(
                'title' => 'Status',
            ))
            ->add('grantStatus', Column::class, array(
                'title' => 'GrantStatus',
            ))
            ->add('requestAmount', Column::class, array(
                'title' => 'RequestAmount',
            ))
            ->add('amount', Column::class, array(
                'title' => 'Amount',
            ))
            ->add('applicant', Column::class, array(
                'title' => 'Applicant',
            ))
            ->add('portalApplication', BooleanColumn::class, array(
                'title' => 'PortalApplication',
            ))
        ;
        $this->initActionButtons();
    }
    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'WelfareBundle\Entity\WelfareApplication';
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'welfareapplication_datatable';
    }
}