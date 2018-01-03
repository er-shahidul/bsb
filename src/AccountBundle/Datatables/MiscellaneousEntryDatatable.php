<?php

namespace AccountBundle\Datatables;

use AccountBundle\Entity\PaymentVoucher;
use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;

/**
 * Class MiscellaneousEntryDatatable
 *
 * @package AccountBundle\Datatables
 */
class MiscellaneousEntryDatatable extends BaseDatatable
{
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $fundTypes = $this->em->getRepository('AccountBundle:FundType')->getOfficeFundType($this->authorizationChecker->isGranted("DASB_USER"));;
        $this->actionButtonType = 'flat';
        $this->features->set($this->getDefaultFeatures());
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('account_sanction_miscellaneous_index')]));
        $this->options->set($this->getDefaultOptions([
            'individual_filtering' => true,
            'order_cells_top' => true,
            'global_search_type' => 'eq',
            'dom' => 'lrtip'
        ]));
        $this->setDefaultExportButtons([0, 2, 3, 4, 5, 6, 7]);

        $this->addActionButton('account_sanction_miscellaneous_edit', 'Edit', 'glyphicon-eye-open', ['id' => 'id'], function($row) {
            return $this->authorizationChecker->isGranted('edit:payment_voucher', $this->getMockObject(PaymentVoucher::class, $row['status']));
        });
        $this->addActionButton('account_sanction_miscellaneous_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder->add('fundType.name', Column::class, [
            'title' => 'Fund Type',
            'filter' => [SelectFilter::class, [
                'search_type' => 'eq',
                'select_options' => ['' => 'All'] + $this->getOptionsArrayFromEntities($fundTypes, 'name', 'name'),
            ]]
        ]);
        $this->addNumberColumn('amount', 'Amount');
        $this->columnBuilder->add('toOrFrom', Column::class, ['title' => 'Payment To']);
        $this->columnBuilder->add('against', Column::class, ['title' => 'Payment Against']);
        $this->columnBuilder->add('description', Column::class, ['title' => 'Description']);
        $this->columnBuilder->add('voucherNumber', Column::class, ['title' => 'Voucher Number']);
        $this->addDateColumn('voucherDate', 'Voucher Date');
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
