<?php

namespace AccountBundle\Datatables;

use AccountBundle\Entity\ChequeReconciliation;
use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;

/**
 * Class ChequeReconciliationDatatable
 *
 * @package AccountBundle\Datatables
 */
class ChequeReconciliationDatatable extends BaseDatatable
{

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'flat';
        $this->features->set($this->getDefaultFeatures());
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('account_reconciliation_index')]));
        $this->options->set($this->getDefaultOptions([
            'individual_filtering' => true,
            'order_cells_top' => true,
            'global_search_type' => 'eq',
            'dom' => 'lrtip'
        ]));
        $this->setDefaultExportButtons([0, 1, 2, 3]);

        $this->addActionButton('account_reconciliation_edit', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
            return $this->authorizationChecker->isGranted('edit:cheque_reconciliation_workflow', $this->getMockObject(ChequeReconciliation::class, $row['status']));
        });
        $this->addActionButton('account_reconciliation_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder->add('fundType.name', Column::class, array('title' => 'Fund Type'));
        $this->columnBuilder->add('year', Column::class, array('title' => 'Year'));
        $this->columnBuilder->add('month', Column::class, array('title' => 'Month'));
        $this->addDateColumn('createdAt', 'Create Date');
        $this->addStatusColumn('status', 'Status');

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AccountBundle\Entity\ChequeReconciliation';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'reconciliation_datatable';
    }
}
