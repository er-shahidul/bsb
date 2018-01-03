<?php

namespace UserBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;

/**
 * Class GroupDatatable
 *
 * @package UserBundle\Datatables
 */
class GroupDatatable extends BaseDatatable
{
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'dropdown';
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('groups_home')]));

        $this->addActionButton('group_update', 'Edit', 'glyphicon-edit', ['id' => 'id']);
        $this->addActionButton('group_delete', 'Delete', 'glyphicon-edit', ['id' => 'id']);

        $this->columnBuilder
            ->add('name', 'column', array(
                'title' => 'Name',
            ))
            ->add('description', 'column', array(
                'title' => 'Description',
            ))
        ;

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'UserBundle\Entity\Group';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'group_datatable';
    }
}
