<?php

namespace MovementBundle\Datatables;

use BudgetBundle\Datatables\Column\HumanizeTextColumnDeprecated;

/**
 * Class GeneralMovementDatatable
 *
 * @package MovementBundle\Datatables
 */
class GeneralMovementDatatable extends MovementDatatable
{
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        parent::configureDataTable();
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('movement_general_list')]));

        if ($this->authorizationChecker->isGranted(['ROLE_DASB_CLERK', 'ROLE_ESTABLISHMENT_CLERK'])) {
            $this->addActionButton('movement_general_edit', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
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
        return 'MovementBundle\Entity\GeneralMovement';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'movement_general_datatable';
    }
}
