<?php

namespace AccountBundle\Datatables;

use AccountBundle\Entity\ChequeIssue;
use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;

/**
 * Class ChequeIssueDatatable
 *
 * @package AccountBundle\Datatables
 */
class ChequeIssueDatatable extends BaseDatatable
{
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'flat';
        $this->features->set($this->getDefaultFeatures());
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('account_cheque_issue_list')]));
        $this->options->set($this->getDefaultOptions());
        $this->setDefaultExportButtons([0, 2]);

        $this->addActionButton('account_cheque_issue_update', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
            return $this->authorizationChecker->isGranted('edit:cheque_issue', $this->getMockObject(ChequeIssue::class, $row['status']));
        });
        $this->addActionButton('account_cheque_issue_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder->add('fundType.name', Column::class, array(
            'title' => 'Name',
        ));
        $this->addDateColumn('createdAt', 'Created At');
        $this->addStatusColumn('status', 'Status');
        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AccountBundle\Entity\ChequeIssue';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'chequeissue_datatable';
    }
}
