<?php

namespace AccountBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;

/**
 * Class FundHeadDatatable
 *
 * @package AccountBundle\Datatables
 */
class FundHeadDatatable extends BaseDatatable
{

    public function getLineFormatter()
    {
        $formatter = function($line){

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
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('account_fundhead_index')]));
        $this->setDefaultExportButtons([0, 1, 2]);

        $this->options->set($this->getDefaultOptions([
            'individual_filtering' => true,
            'order_cells_top' => true,
            'dom' => 'lrtip'
        ]));

        $this->addActionButton('account_fundhead_edit', 'Edit', 'glyphicon-eye-open', ['id' => 'id']);

        $fundTypes = $this->em->getRepository('AccountBundle:FundType')->findAll();
       // $officeType = $this->em->getRepository('AppBundle:OfficeType')->findAll();
        $this->columnBuilder
            ->add('officeType.name', Column::class, array(
                'visible' => FALSE
            ))
            ->add('fundType.name', Column::class, array(
                'title' => 'Fund Type',
                'orderable' => FALSE,
                'filter' => array(SelectFilter::class, array(
                    'search_type' => 'eq',
                    'select_options' => array('' => 'All') + $this->getOptionsArrayFromEntities($fundTypes, 'name', 'name'),
                ))
            ))
            ->add('name', Column::class, array(
                'title' => 'Fund Head',
                'orderable' => FALSE
                ))
            ->add('sort', Column::class, array(
                'title' => 'Sort',
                'orderable' => FALSE
                ))
            ;

        $this->callbacks->set(array(
            'draw_callback' => array(
                'template' => '@Account/FundHead/datatable/_fund_head_draw_callback.js.twig'
            )
        ));
        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AccountBundle\Entity\FundHead';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'fundhead_datatable';
    }
}
