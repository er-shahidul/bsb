<?php

namespace FuneralBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use BudgetBundle\Datatables\Column\HumanizeTextColumnDeprecated;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

/**
 * Class FuneralDatatable
 *
 * @package FuneralBundle\Datatables
 */
class FuneralDatatable extends BaseDatatable
{
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'flat';
        $this->features->set($this->getDefaultFeatures());
        $this->options->set($this->getDefaultOptions());
        $this->setDefaultExportButtons([1,2,3]);

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('funeral_list')]));

        $this->addActionButton('funeral_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        if ($this->authorizationChecker->isGranted(['ROLE_DASB_CLERK'])) {
            $this->addActionButton('funeral_edit', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
                return $row['status'] == 'draft' || empty($row['status']);
            });
        }

        $this->columnBuilder
            ->add('deceasedDate', DateTimeColumn::class, array(
                'title' => 'Deceased Date',
                'date_format' => 'YYYY-MM-DD'
            ))
            ->add('exServiceman.identityNumber', Column::class, array(
                'title' => 'Personal No',
            ))
            ->add('exServiceman.name', Column::class, array(
                'title' => 'Name',
            ))
            ->add('office.name', Column::class, array(
                'title' => 'Office',
            ))
            ->add('amount', Column::class, array(
                'title' => 'Amount',
            ))
        ;
        $this->addStatusColumn('status', 'Status');
        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'FuneralBundle\Entity\Funeral';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'funeral_datatable';
    }
}
