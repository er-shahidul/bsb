<?php

namespace AccountBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;

/**
 * Class MiscellaneousEntryDatatable
 *
 * @package AccountBundle\Datatables
 */
class PaymentVouchersDatatable extends BaseDatatable
{
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $fundTypes = $this->em->getRepository('AccountBundle:FundType')->getOfficeFundType($this->authorizationChecker->isGranted("DASB_USER"));;
        $this->actionButtonType = 'flat';
        $this->features->set($this->getDefaultFeatures());
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('account_payment_vouchers_index')]));
        $this->options->set($this->getDefaultOptions([
            'individual_filtering' => true,
            'order_cells_top' => true,
            'global_search_type' => 'eq',
            'dom' => 'lrtip'
        ]));
        $this->setDefaultExportButtons([0, 2, 3, 4, 5, 6, 7]);

        $this->addActionButton('account_payment_vouchers_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder->add('voucherNumber', Column::class, [
            'title' => 'Voucher Number'
        ]);
        $this->columnBuilder->add('chequeNumber', Column::class, [
            'title' => 'Cheque Number'
        ]);

        $this->addNumberColumn('amount', 'Amount');
        $this->columnBuilder
            ->add('toOrFrom', Column::class, array(
                'title' => 'Payment To',
            ))
            ->add('against', Column::class, array(
                'title' => 'Payment Against',
            ))
            ->add('fundType.name', Column::class, array(
                'title' => 'Fund Type',
                'filter' => array(SelectFilter::class, array(
                    'search_type' => 'eq',
                    'select_options' => array('' => 'All') + $this->getOptionsArrayFromEntities($fundTypes, 'name', 'name'),
                ))
            ));
        $this->columnBuilder->add('reconciled', BooleanColumn::class, [
            'title' => 'Reconciled',
            'true_label' => 'Yes',
            'false_label' => 'No',
        ]);
        $this->columnBuilder->add('debited', BooleanColumn::class, [
            'title' => 'Debited',
            'true_label' => 'Yes',
            'false_label' => 'No',
        ]);
        $this->addStatusColumn('status', 'Status');

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AccountBundle\Entity\PaymentVoucher';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'SanctionMiscellaneous_datatable';
    }
}
