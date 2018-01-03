<?php

namespace AccountBundle\Datatables;

use AccountBundle\Entity\SanctionPayment;
use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;

/**
 * Class SanctionDatatable
 *
 * @package AccountBundle\Datatables
 */
class SanctionPaymentEntryDatatable extends BaseDatatable
{

    public function getCustomLineFormatter()
    {
        $formatter = function($line ) {
            $line['statusVirtual'] = 'Custom Status';
            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $fundTypes = $this->em->getRepository('AccountBundle:FundType')->getOfficeFundType($this->authorizationChecker->isGranted("DASB_USER"));
        $this->actionButtonType = 'flat';
        $this->features->set($this->getDefaultFeatures());
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('account_sanction_entry_index')]));
        $this->options->set($this->getDefaultOptions([
            'individual_filtering' => true,
            'order_cells_top' => true,
            'global_search_type' => 'eq',
            'dom' => 'lrtip'
        ]));
        $this->setDefaultExportButtons([0, 2, 3, 4, 5, 6, 7]);

        $this->addActionButton('account_sanction_payment_edit', 'Edit', 'glyphicon-eye-open', ['id' => 'id'], function($row) {
            return $this->authorizationChecker->isGranted('edit:sanction_payment_workflow', $this->getMockObject(SanctionPayment::class, $row['status']));
        });
        $this->addActionButton('account_sanction_payment_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder
            ->add('noteSheetNumber', Column::class, array(
                'title' => 'Note Sheet No.',
                ));

        $this->addDateColumn('voucherDate', 'Voucher Date');

        $this->addNumberColumn('amount', 'Amount');
        $this->columnBuilder->add('description', Column::class, array(
                'title' => 'Description',
                ))
            ->add('fundType.name', Column::class, array(
                'title' => 'Fund Type',
                'filter' => array(SelectFilter::class, array(
                    'search_type' => 'eq',
                    'select_options' => array('' => 'All') + $this->getOptionsArrayFromEntities($fundTypes, 'name', 'name'),
                    ))
                ));

        $this->addStatusColumn('status', 'Status');

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AccountBundle\Entity\SanctionPayment';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sanctionentry_datatable';
    }
}
