<?php

namespace WelfareBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use BudgetBundle\Datatables\Column\HumanizeTextColumnDeprecated;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
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
 * Class MicroCreditPaymentDatatable
 *
 * @package WelfareBundle\Datatables
 */
class MicroCreditPaymentDatatable extends BaseDatatable
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
        $this->actionButtonType = 'dropdown';
        $this->features->set($this->getDefaultFeatures());
        $this->options->set($this->getDefaultOptions());
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('welfare_micro_credit_payment_index')]));
        $this->setDefaultExportButtons([1,2,3]);

        $this->addActionButton('welfare_micro_credit_payment_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        if ($this->authorizationChecker->isGranted(['ROLE_DASB_CLERK'])) {
            $this->addActionButton('welfare_micro_credit_payment_receive_edit', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
                return HumanizeTextColumnDeprecated::reformStatusValue($row['status']) == 'draft' || empty($row['status']);
            });
        }

        $this->columnBuilder
            ->add('application.office.name', Column::class, array(
                'title' => 'Office',
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
            ->add('paymentAmount', Column::class, array(
                'title' => 'Payment Amount',
            ))
            ->add('date', DateTimeColumn::class, array(
                'title' => 'Date',
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
        return 'WelfareBundle\Entity\MicroCreditPayment';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'microCreditPayment_datatable';
    }
}
