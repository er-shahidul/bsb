<?php

namespace MovementBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;

/**
 * Class MovementDatatable
 *
 * @package MovementBundle\Datatables
 */
class MovementDatatable extends BaseDatatable
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

        $this->columnBuilder
            ->add('startDate', DateTimeColumn::class, array(
                'title' => 'Start Date',
                'date_format' => 'YYYY-MM-DD'
            ))
            ->add('endDate', DateTimeColumn::class, array(
                'title' => 'End Date',
                'date_format' => 'YYYY-MM-DD'
            ))
            ->add('travelBy', Column::class, array(
                'title' => 'TravelBy',
            ))
            ->add('travelPlan', Column::class, array(
                'title' => 'TravelPlan',
            ))
            ->add('visitors.name', Column::class, array(
                'title' => 'Visitor',
                'data' => 'visitors[, ].name'
            ))
            ->add('destinations.name', Column::class, array(
                'title' => 'Destinations',
                'data' => 'destinations[, ].name'
            ))
            ->add('office.name', Column::class, array(
                'title' => 'Office',
            ))
        ;
        $this->addStatusColumn('status', 'Status');
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'MovementBundle\Entity\Movement';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'movement_datatable';
    }
}
