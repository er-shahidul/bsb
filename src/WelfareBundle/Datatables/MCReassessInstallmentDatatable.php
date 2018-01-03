<?php

namespace WelfareBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use BudgetBundle\Datatables\Column\HumanizeTextColumnDeprecated;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\NumberColumn;
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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Class MCReassessInstallmentDatatable
 *
 * @package WelfareBundle\Datatables
 */
class MCReassessInstallmentDatatable extends BaseDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($line) {
            $line['totalDue'] = number_format($line['application']['amount'] - $line['application']['microCreditDetail']['totalPaid']);
            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'flat';
        $this->features->set($this->getDefaultFeatures());
        $this->options->set($this->getDefaultOptions());
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('welfare_mc_reassess_installment_index')]));
        $this->setDefaultExportButtons([1,2,3]);

        $this->addActionButton('welfare_mc_reassess_installment_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder

            ->add('office.name', Column::class, array(
                'title' => 'Office',
            ))
            ->add('application.serviceMan.name', Column::class, array(
                'title' => 'Applicant',
            ))
            ->add('application.serviceMan.rank.name', Column::class, array(
                'title' => 'Rank',
            ))
            ->add('application.serviceMan.identityNumber', Column::class, array(
                'title' => 'Soldier No.',
            ))
            ->add('application.microCreditDetail.projectType.id', Column::class, array(
                'title' => 'Project Type',
            ));

        $this->addNumberColumn('application.amount', 'Granted(TK)');
        $this->addNumberColumn('application.microCreditDetail.totalPaid', 'Total Paid(TK)');
        $this->columnBuilder
            ->add('totalDue', VirtualColumn::class, array(
                'title' => 'Total Due(TK)',
                'searchable' => false,
                'orderable' => false
            ));

        $this->addStatusColumn('status', 'Status');

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'WelfareBundle\Entity\MCReassessInstallment';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'MCReassessInstallmentDatatable_datatable';
    }
}
