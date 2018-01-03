<?php

namespace WelfareBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use BudgetBundle\Datatables\Column\HumanizeTextColumnDeprecated;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\NumberColumn;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\MultiselectColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\ImageColumn;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Filter\NumberFilter;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;
use Sg\DatatablesBundle\Datatable\Editable\CombodateEditable;
use Sg\DatatablesBundle\Datatable\Editable\SelectEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextareaEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextEditable;

/**
 * Class MCDefaulterRegisterDatatable
 *
 * @package WelfareBundle\Datatables
 */
class MCDefaulterRegisterDatatable extends BaseDatatable
{

    public function getLineFormatter()
    {
        $formatter = function($line) {
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
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('welfare_mc_defaulter_register_index')]));
        $this->setDefaultExportButtons([0,1]);

        $this->addActionButton('welfare_mc_defaulter_register_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $officeTypes = $this->em->getRepository('AppBundle:Office')->createQueryBuilder('o')
            ->join('o.officeType', 't')->where('t.name = :office')->setParameter('office', 'DASB')
            ->getQuery()->getResult();

        $this->columnBuilder

            ->add('office.name', Column::class, array(
                'title' => 'DASB',
                'filter' => array(SelectFilter::class, array(
                    'search_type' => 'eq',
                    'select_options' => array('' => 'All') + $this->getOptionsArrayFromEntities($officeTypes, 'name', 'name'),
                )),
            ))
            ->add('createdAt', DateTimeColumn::class, array(
                'title' => 'Create Date',
                'date_format' => 'DD-MM-YYYY',
            ));

        $this->addStatusColumn('status', 'Status');

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'WelfareBundle\Entity\MCDefaulterRegister';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'MCDefaulterRegisterDatatable_datatable';
    }
}
