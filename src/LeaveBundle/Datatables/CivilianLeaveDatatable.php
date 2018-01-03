<?php

namespace LeaveBundle\Datatables;

/**
 * Class CivilianLeaveDatatable
 *
 * @package LeaveBundle\Datatables
 */
class CivilianLeaveDatatable extends LeaveDatatable
{
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        parent::configureDataTable();
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('leave_civilian_list')]));

        if ($this->authorizationChecker->isGranted(['ROLE_ESTABLISHMENT_CLERK'])) {
            $this->addActionButton('leave_civilian_edit', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
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
        return 'LeaveBundle\Entity\CivilianLeave';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'leave_civilian_datatable';
    }
}
