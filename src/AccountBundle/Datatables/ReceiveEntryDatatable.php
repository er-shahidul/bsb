<?php

namespace AccountBundle\Datatables;

use AccountBundle\Entity\ReceiveVoucher;
use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;

/**
 * Class SanctionDatatable
 *
 * @package AccountBundle\Datatables
 */
class ReceiveEntryDatatable extends BaseDatatable
{

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $fundTypes = $this->em->getRepository('AccountBundle:FundType')->getOfficeFundType($this->authorizationChecker->isGranted("DASB_USER"));
        $this->actionButtonType = 'flat';
        $this->features->set($this->getDefaultFeatures());
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('account_sanction_receive_index')]));
        $this->options->set($this->getDefaultOptions([
            'individual_filtering' => true,
            'order_cells_top' => true,
            'global_search_type' => 'eq',
            'dom' => 'lrtip'
        ]));
        $this->setDefaultExportButtons([0, 1, 2, 3, 4, 5, 7]);

        $this->addActionButton('account_sanction_receive_edit', 'Edit', 'glyphicon-eye-open', ['id' => 'id'], function($row) {
            return $this->authorizationChecker->isGranted('edit:receive_voucher', $this->getMockObject(ReceiveVoucher::class, $row['status']));
        });
        $this->addActionButton('account_sanction_receive_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder->add('fundType.name', Column::class, [
                'title' => 'Fund Type',
                'filter' => [SelectFilter::class, [
                    'search_type' => 'eq',
                    'select_options' => ['' => 'All'] + $this->getOptionsArrayFromEntities($fundTypes, 'name', 'name'),
                ]]
            ]);
        $this->addNumberColumn('amount', 'Amount');
        $this->columnBuilder->add('toOrFrom', Column::class, ['title' => 'Received From']);
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
        return 'AccountBundle\Entity\ReceiveVoucher';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'receiveentry_datatable';
    }
}
