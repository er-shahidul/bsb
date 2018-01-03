<?php

namespace MovementBundle\Datatables;

/**
 * Class DirectorMovementDatatable
 *
 * @package MovementBundle\Datatables
 */
class DirectorMovementDatatable extends MovementDatatable
{
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        parent::configureDataTable();
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('movement_director_list')]));

        if ($this->authorizationChecker->isGranted(['ROLE_ESTABLISHMENT_CLERK'])) {
            $this->addActionButton('movement_director_edit', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
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
        return 'MovementBundle\Entity\DirectorMovement';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'movement_director_datatable';
    }
}
