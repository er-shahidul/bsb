<?php

namespace MovementBundle\Datatables;

/**
 * Class SecretaryMovementDatatable
 *
 * @package MovementBundle\Datatables
 */
class SecretaryMovementDatatable extends MovementDatatable
{
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        parent::configureDataTable();
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('movement_secretary_list')]));

        if ($this->authorizationChecker->isGranted(['ROLE_DASB_CLERK'])) {
            $this->addActionButton('movement_secretary_edit', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
                return $row['status'] == 'draft' || empty($row['status']);
            });
        }

        $this->addActionButton('movement_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'MovementBundle\Entity\SecretaryMovement';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'movement_secretary_datatable';
    }
}
