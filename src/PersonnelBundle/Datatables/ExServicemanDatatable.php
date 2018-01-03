<?php

namespace PersonnelBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

/**
 * Class ExServicemanDatatable
 *
 * @package PersonnelBundle\Datatables
 */
class ExServicemanDatatable extends BaseDatatable
{

    public function getLineFormatter()
    {
        $formatter = function ($line) {
            $line['deceasedView'] = $line['deceased'] ? 'Yes' : 'No';

            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'flat';
        $this->features->set($this->getDefaultFeatures());
        $this->options->set($this->getDefaultOptions());
        $this->setDefaultExportButtons([1,2,3]);

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('ex_serviceman_list')]));

        if ($this->authorizationChecker->isGranted(['ROLE_DASB_CLERK'])) {
            $this->addActionButton('ex_serviceman_edit', 'Edit', 'glyphicon glyphicon-edit', ['id' => 'id']);
        }

        $this->addActionButton('ex_serviceman_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder
            ->add('id', Column::class, array(
                'visible' => false,
                ))
            ->add('name', Column::class, array(
                'title' => 'Name',
            ))
            ->add('tsNumber', Column::class, array(
                'title' => 'TS Number',
                ))
            ->add('identityNumber', Column::class, array(
                'title' => 'Personal No',
                ))
            ->add('service.id', Column::class, array(
                'title' => 'Service Group',
                ))
            ->add('rank.short', Column::class, array(
                'title' => 'Rank',
                'default_content' => ''
                ))
            ->add('office.name', Column::class, array(
                'title' => 'DASB Office',
                'default_content' => ''
                ))
            ->add('retirementDate', DateTimeColumn::class, array(
                'title' => 'Retirement Date',
                'date_format' => 'YYYY-MM-DD'
            ))
            ->add('district.name', Column::class, array(
                'title' => 'Dictrict',
                'default_content' => ''
                ))
            ->add('deceased', Column::class, array(
                'visible' => FALSE
            ))
            ->add('deceasedView', VirtualColumn::class, array(
                'title' => 'Deceased',
            ))
            ;

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'PersonnelBundle\Entity\ExServiceman';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'exserviceman_datatable';
    }
}
