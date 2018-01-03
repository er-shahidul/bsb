<?php

namespace LeaveBundle\Datatables;

/**
 * Class MilitaryLeaveDatatable
 *
 * @package LeaveBundle\Datatables
 */
class MilitaryLeaveDatatable extends LeaveDatatable
{
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        parent::configureDataTable();
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('leave_general_list')]));

        if ($this->authorizationChecker->isGranted(['ROLE_ESTABLISHMENT_CLERK'])) {
            $this->addActionButton('leave_military_edit', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
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
        return 'LeaveBundle\Entity\MilitaryLeave';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'leave_military_datatable';
    }
}
