<?php
namespace BudgetBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use AppBundle\Entity\FinancialYear;
use BudgetBundle\Datatables\Column\HumanizeTextColumnDeprecated;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\NumberColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

/**
 * Class BudgetExpenseDatatable
 *
 * @package BudgetBundle\Datatables
 */
class BudgetExpenseSanctionDatatable extends BaseDatatable
{
    public function getCustomLineFormatter()
    {
        $formatter = function($line){
            $line['financialYear'] = FinancialYear::getFinancialLabel($line['budgetExpense']['financialYear']['id']);

            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $formatter = new \NumberFormatter("en-US", \NumberFormatter::DECIMAL);
        $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 2);

        $this->actionButtonType = 'flat';

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('budget_expense_bill_sanction_list')]));

        if ($this->authorizationChecker->isGranted(['ROLE_BUDGET_CLERK', 'ROLE_DASB_CLERK'])) {
            $this->addActionButton('budget_expense_bill_sanction_update', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row){
                return ($this->authorizationChecker->isGranted('ROLE_BUDGET_CLERK') && HumanizeTextColumnDeprecated::reformStatusValue($row['status']) == 'draft');
            });
        }
        $this->addActionButton('budget_expense_bill_sanction_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);


        $this->columnBuilder
            ->add('budgetExpense.financialYear.id', Column::class, array(
                'visible' => false,
            ))
            ->add('financialYear', VirtualColumn::class, array(
            'title' => 'Financial Year',
            ));

        if ($this->authorizationChecker->isGranted('BASB_USER')) {
            $this->columnBuilder->add('budgetExpense.office.name', Column::class, array(
                'title' => 'Office <br>Name'
            ));
        }

        $this->columnBuilder->add('budgetExpense.budgetHead.code', Column::class, array(
                'title' => 'Code',
            ))
            ->add('budgetExpense.budgetHead.titleBn', Column::class, array(
                'title' => 'Budget Head',
            ))
            ->add('budgetExpense.letterNo', Column::class, array(
                'title' => 'Letter No',
            ))
            ->add('budgetExpense.letterDate', DateTimeColumn::class, array(
                'title' => 'Letter Date',
                'date_format' => 'YYYY-MM-DD'
            ));
        $this->addNumberColumn('budgetExpense.amount', 'Amount <br>Demanded');
        $this->addNumberColumn('totalAmount', 'Amount <br>Passed');

        $this->columnBuilder->add('chequeLipiNo', Column::class, array(
                'title' => 'Cheque <br>Lipi No',
            ))
            ->add('chequeLipiDate', DateTimeColumn::class, array(
                'title' => 'Cheque Lipi <br>Date',
                'date_format' => 'YYYY-MM-DD'
            ))
            ->add('status', HumanizeTextColumnDeprecated::class, array(
                'title' => 'Status',
            ))
            ;

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'BudgetBundle\Entity\BudgetExpenseSanction';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'budget_approved_expense_datatable';
    }
}
