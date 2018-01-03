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
class ServingMilitaryDatatable extends BaseDatatable
{

    public function getLineFormatter()
    {
        $formatter = function ($line) {
            $military = $line['militaryProfile'];
            $line['regimentAndCorp'] = $line['corp_name'] . ' ' . $military['regimentalNumber'];
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

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('serving_military_list')]));
        $this->addActionButton('serving_military_edit', 'Edit', 'glyphicon glyphicon-edit', ['id' => 'id']);
        $this->addActionButton('serving_military_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder
            ->add('id', Column::class, array(
                'visible' => false,
                ))
            ->add('name', Column::class, array(
                'title' => 'Name',
            ))
            ->add('identityNumber', Column::class, array(
                'title' => 'Personal No',
                ))
            ->add('service_name', Column::class, array(
                'title' => 'Service Group',
                'dql' => '(SELECT {s}.id FROM PersonnelBundle:Service {s} WHERE {s}.id = militaryProfile.service)',
                'searchable' => true,
                'orderable' => true,
                ))
            ->add('rank_name', Column::class, array(
                'title' => 'Rank',
                'dql' => '(SELECT {r}.name FROM PersonnelBundle:Rank {r} WHERE {r}.id = militaryProfile.rank)',
                'searchable' => true,
                'orderable' => true,
            ))
            ->add('corp_name', Column::class, array(
                'visible' => FALSE,
                'dql' => '(SELECT {c}.name FROM PersonnelBundle:Corp {c} WHERE {c}.id = militaryProfile.corp)',
                'searchable' => FALSE,
                'orderable' => true,
            ))
            ->add('militaryProfile.regimentalNumber', Column::class, array(
                'visible' => FALSE
            ))->add('regimentAndCorp', VirtualColumn::class, array(
                'title' => 'Regiment',
            ))
            ->add('designation', Column::class, array(
                'title' => 'Designation',
                ))
            ->add('office.name', Column::class, array(
                'title' => 'Office',
                ))
            ->add('mobileNumber', Column::class, array(
                'title' => 'Mobile No',
            ))
            ->add('joiningDate', DateTimeColumn::class, array(
                'title' => 'Joining Date',
                'date_format' => 'YYYY-MM-DD'
            ))
            ;

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'PersonnelBundle\Entity\ServingMilitary';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'serving_military_datatable';
    }
}
