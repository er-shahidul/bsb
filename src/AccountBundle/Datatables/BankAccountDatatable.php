<?php

namespace AccountBundle\Datatables;

use AccountBundle\Entity\BankAccount;
use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;

/**
 * Class FundHeadDatatable
 *
 * @package AccountBundle\Datatables
 */
class BankAccountDatatable extends BaseDatatable
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
        $this->features->set($this->getDefaultFeatures());
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('account_bank_account_index')]));
        $this->options->set($this->getDefaultOptions());
        $this->setDefaultExportButtons([0, 1, 2, 3]);

        $this->addActionButton('account_bank_account_edit', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
            return $this->authorizationChecker->isGranted('edit:accounts_bank_account', $this->getMockObject(BankAccount::class, $row['status']));
        });
        $this->addActionButton('account_bank_account_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder
            ->add('name', Column::class, array(
                'title' => 'Name',
                ))
            ->add('fundType.name', Column::class, array(
                'title' => 'Fund Type',
                ))
            ->add('accountNumber', Column::class, array(
                'title' => 'Account Number',
                ))
            ->add('bankBranch.bank.name', Column::class, array(
                'title' => 'Bank Name',
                ))
            ->add('bankBranch.name', Column::class, array(
                'title' => 'Branch Name',
                ));

        $this->addStatusColumn('status', 'Status');

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AccountBundle\Entity\BankAccount';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bankaccount_datatable';
    }
}
