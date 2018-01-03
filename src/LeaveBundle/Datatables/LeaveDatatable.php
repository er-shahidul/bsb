<?php

namespace LeaveBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;

/**
 * Class LeaveDatatable
 *
 * @package LeaveBundle\Datatables
 */
class LeaveDatatable extends BaseDatatable
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
            ->add('identityNumber.identityNumber', Column::class, array(
                'title' => 'Personal No',
            ))
            ->add('identityNumber.name', Column::class, array(
                'title' => 'Name',
            ))
            ->add('startDate', DateTimeColumn::class, array(
                'title' => 'Start Date',
                'date_format' => 'YYYY-MM-DD'
            ))
            ->add('endDate', DateTimeColumn::class, array(
                'title' => 'End Date',
                'date_format' => 'YYYY-MM-DD'
            ))
            ->add('numberOfDate', Column::class, array(
                'title' => 'Number Of Days',
            ))
            ->add('typeOfLeave', Column::class, array(
                'title' => 'Leave Type',
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
        return 'LeaveBundle\Entity\Leave';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'leave_datatable';
    }
}
