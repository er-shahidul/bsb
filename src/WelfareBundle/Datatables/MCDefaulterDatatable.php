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
 * Class MCDefaulterDatatable
 *
 * @package WelfareBundle\Datatables
 */
class MCDefaulterDatatable extends BaseDatatable
{

    public function getLineFormatter()
    {
        $formatter = function($line) {
            $payable = $this->getEntityManager()->getRepository('WelfareBundle:MicroCreditApplication')->totalPayable($line['id']);
            $line['totalPayable'] = number_format($payable);
            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'dropdown';
        $this->features->set($this->getDefaultFeatures());
        $this->options->set($this->getDefaultOptions());
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('welfare_micro_credit_defaulters')]));
        $this->setDefaultExportButtons([0]);

        $this->addActionButton('welfare_mc_reassess_installment_create', 'Reassess Installment', null, ['id' => 'application.id']);
        $this->addActionButton('welfare_micro_credit_payment_history', 'Payment History', null, ['id' => 'application.id']);

        $this->columnBuilder
            ->add('id', Column::class, array(
                'title' => 'Applicant',
                'visible' => false
            ))
            ->add('application.serviceMan.name', Column::class, array(
                'title' => 'Applicant',
            ))
            ->add('application.serviceMan.rank.name', Column::class, array(
                'title' => 'Rank',
            ))
            ->add('application.serviceMan.identityNumber', Column::class, array(
                'title' => 'Soldier No.',
            ))
            ->add('application.microCreditDetail.projectType.id', Column::class, array(
                'title' => 'Project Type',
            ));
        $this->addNumberColumn('application.amount', 'Grant Amount');
        $this->addNumberColumn('application.microCreditDetail.totalPaid', 'Total Paid');

        $this->columnBuilder
            ->add('totalPayable', VirtualColumn::class, array(
                'title' => 'Total Payable',
                'searchable' => false,
                'orderable' => false
            ));

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'WelfareBundle\Entity\MCDefaulter';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'MCDefaulterDatatable_datatable';
    }
}
