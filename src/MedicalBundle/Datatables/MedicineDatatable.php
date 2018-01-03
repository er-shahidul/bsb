<?php

namespace MedicalBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;

/**
 * Class MedicineDatatable
 *
 * @package MedicalBundle\Datatables
 */
class MedicineDatatable extends BaseDatatable
{

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->features->set($this->getDefaultFeatures());
        $this->options->set($this->getDefaultOptions());
        $this->setDefaultExportButtons([1,2]);

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('medical_medicine_list')]));
        $this->addActionButton('medical_medicine_edit', 'Edit', 'glyphicon glyphicon-edit', ['id' => 'id']);

        $this->columnBuilder
            ->add('id', Column::class, array(
                'visible' => FALSE
                ))
            ->add('name', Column::class, array(
                'title' => 'Name',
                ))
            ->add('accountUnit', Column::class, array(
                'title' => 'AccountUnit',
                ))
            ->add('enabled', BooleanColumn::class, array(
                'title' => 'Enabled',
                'searchable' => true,
                'orderable' => true,
                'true_label' => 'Yes',
                'false_label' => 'No',
                'true_icon' => 'glyphicon glyphicon-ok',
                'false_icon' => 'glyphicon glyphicon-remove',

            ))
            ;

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'MedicalBundle\Entity\Medicine';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'medicine_datatable';
    }
}
