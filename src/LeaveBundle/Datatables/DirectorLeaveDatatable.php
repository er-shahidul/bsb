<?php

namespace LeaveBundle\Datatables;

/**
 * Class DirectorLeaveDatatable
 *
 * @package LeaveBundle\Datatables
 */
class DirectorLeaveDatatable extends LeaveDatatable
{
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        parent::configureDataTable();
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('leave_director_list')]));

        if ($this->authorizationChecker->isGranted(['ROLE_ESTABLISHMENT_CLERK'])) {
            $this->addActionButton('leave_director_edit', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
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
        return 'LeaveBundle\Entity\DirectorLeave';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'leave_director_datatable';
    }
}
