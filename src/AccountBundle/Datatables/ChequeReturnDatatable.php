<?php

namespace AccountBundle\Datatables;

use AccountBundle\Entity\ChequeReturn;
use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;

/**
 * Class ChequeReturnDatatable
 *
 * @package AccountBundle\Datatables
 */
class ChequeReturnDatatable extends BaseDatatable
{

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'flat';
        $this->features->set($this->getDefaultFeatures());
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('account_cheque_return_index')]));
        $this->options->set($this->getDefaultOptions([
            'individual_filtering' => true,
            'order_cells_top' => true,
            'global_search_type' => 'eq',
            'dom' => 'lrtip'
        ]));
        $this->setDefaultExportButtons([0, 1, 2, 3]);

        $this->addActionButton('account_cheque_return_edit', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
            return $this->authorizationChecker->isGranted('edit:cheque_return_workflow', $this->getMockObject(ChequeReturn::class, $row['status']));
        });
        $this->addActionButton('account_cheque_return_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder->add('fundType.name', Column::class, array(
            'title' => 'Bank Account',
        ));
        $this->addDateColumn('createdAt', 'Create Date');
        $this->addStatusColumn('status', 'Status');

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AccountBundle\Entity\ChequeReturn';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'reconciliation_datatable';
    }
}
