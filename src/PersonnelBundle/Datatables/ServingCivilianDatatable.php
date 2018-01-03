<?php

namespace PersonnelBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;

/**
 * Class ExServicemanDatatable
 *
 * @package PersonnelBundle\Datatables
 */
class ServingCivilianDatatable extends BaseDatatable
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

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('serving_civilian_list')]));
        $this->addActionButton('serving_civilian_edit', 'Edit', 'glyphicon glyphicon-edit', ['id' => 'id']);
        $this->addActionButton('serving_civilian_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder
            ->add('id', Column::class, array(
                'visible' => false,
                ))
            ->add('name', Column::class, array(
                'title' => 'Name',
            ))
            ->add('identityNumber', Column::class, array(
                'title' => 'BASB ID No',
            ))
            ->add('designation', Column::class, array(
                'title' => 'Rank',
                ))
            ->add('office.name', Column::class, array(
                'title' => 'Office',
                ))
            ->add('mobileNumber', Column::class, array(
                'title' => 'Mobile No',
            ))
            ->add('joiningDate', DateTimeColumn::class, array(
                'title' => 'Joining Date',
                'date_format' => 'YYYY-MM-DD'
            ))
            ;

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'PersonnelBundle\Entity\ServingCivilian';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'civilian_datatable';
    }
}
