<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\Column\Column;

/**
 * Class OfficeDatatable
 *
 * @package AppBundle\Datatables
 */
class OfficeDatatable extends BaseDatatable
{
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'flat';
        $this->features->set($this->getDefaultFeatures());
        $this->options->set($this->getDefaultOptions());
//        $this->setDefaultExportButtons([1,2,3]);

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('office_list')]));
        $this->addActionButton('office_edit', 'Edit', 'glyphicon glyphicon-edit', ['id' => 'id']);

        $this->columnBuilder
            ->add('id', Column::class, array(
                'title' => 'Id',
                ))
            ->add('name', Column::class, array(
                'title' => 'Name',
                ))
            ->add('address', Column::class, array(
                'title' => 'Address',
                ))
            ->add('phone', Column::class, array(
                'title' => 'Phone',
                ))
            ->add('fax', Column::class, array(
                'title' => 'Fax',
                ))
            ->add('email', Column::class, array(
                'title' => 'Email',
                ))
            ->add('geoCode', Column::class, array(
                'title' => 'GeoCode',
                ))
            ->add('officeType.name', Column::class, array(
                'title' => 'Office Type',
                ))
            ;

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\Office';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'office_datatable';
    }
}
