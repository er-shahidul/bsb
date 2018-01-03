<?php

namespace LeaveBundle\Datatables;

use BudgetBundle\Datatables\Column\HumanizeTextColumnDeprecated;

/**
 * Class SecretaryLeaveDatatable
 *
 * @package LeaveBundle\Datatables
 */
class SecretaryLeaveDatatable extends LeaveDatatable
{
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        parent::configureDataTable();
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('leave_secretary_list')]));

        if ($this->authorizationChecker->isGranted(['ROLE_DASB_CLERK'])) {
            $this->addActionButton('leave_secretary_edit', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
                return $row['status'] == 'draft' || empty($row['status']);
            });
        }

        $this->addActionButton('leave_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'LeaveBundle\Entity\SecretaryLeave';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'leave_secretary_datatable';
    }
}
