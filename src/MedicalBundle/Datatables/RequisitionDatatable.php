<?php

namespace MedicalBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use MedicalBundle\Entity\Requisition;
use Sg\DatatablesBundle\Datatable\Column\Column;

/**
 * Class RequisitionDatatable
 *
 * @package MedicalBundle\Datatables
 */
class RequisitionDatatable extends BaseDatatable
{

    public function getCustomLineFormatter()
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
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('medical_requisition_list')]));
        $this->setDefaultExportButtons([1,2,3]);

        $this->addActionButton('medical_requisition_update', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
            return $this->authorizationChecker->isGranted('edit:medical_requisition_workflow', $this->getMockObject(Requisition::class, $row['status']));
        });
        $this->addActionButton('medical_requisition_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder->add('dispensary.name', Column::class, array(
            'title' => 'Dispensary Name',
        ));
        $this->addDateColumn('createdAt', 'Created At');
        $this->addStatusColumn('status', 'Stauts');

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'MedicalBundle\Entity\Requisition';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'requisition_datatable';
    }
}
