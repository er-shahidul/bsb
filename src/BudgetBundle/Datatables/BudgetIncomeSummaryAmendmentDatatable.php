<?php
namespace BudgetBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use BudgetBundle\Datatables\Column\FinancialYearColumn;
use BudgetBundle\Datatables\Column\HumanizeTextColumnDeprecated;
use Sg\DatatablesBundle\Datatable\Column\NumberColumn;

/**
 * Class BudgetSummaryDatatable
 *
 * @package BudgetBundle\Datatables
 */
class BudgetIncomeSummaryAmendmentDatatable extends BaseDatatable
{
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'dropdown';

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('budget_income_amendment_list')]));

        $this->addActionButton('budget_income_amendment_update', 'Edit', 'glyphicon-edit', ['id' => 'id'], function ($row) {
            return $this->authorizationChecker->isGranted('ROLE_BUDGET_CLERK') && HumanizeTextColumnDeprecated::reformStatusValue($row['amendmentStatus']) == 'draft';
        });
        $this->addActionButton('budget_income_amendment_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder
            ->add('financialYear.id', FinancialYearColumn::class, array(
                'title' => 'Financial Year',
            ))
            ->add('requestAmount', NumberColumn::class, array(
                'formatter' => $this->currencyFormatter,
                'class_name' => 'text-right',
                'title' => 'Amount',
                'dql' => "(
                        SELECT SUM({budgetDetail}.requestAmount) 
                        FROM BudgetBundle:BudgetIncomeSummaryDetail {budgetDetail} 
                        WHERE {budgetDetail}.budgetSummary = {budgetincomesummary}.id GROUP BY {budgetDetail}.budgetSummary)",
                'searchable' => false,
                'orderable' => false,
                'type_of_field' => 'integer',
            ))
            ->add('amendmentRequestAmount', NumberColumn::class, array(
                'formatter' => $this->currencyFormatter,
                'class_name' => 'text-right',
                'title' => 'Revised Amount',
                'dql' => "(
                        SELECT SUM({budgetDetail2}.amendmentRequestAmount) 
                        FROM BudgetBundle:BudgetIncomeSummaryDetail {budgetDetail2} 
                        WHERE {budgetDetail2}.budgetSummary = {budgetincomesummary}.id GROUP BY {budgetDetail2}.budgetSummary)",
                'searchable' => false,
                'orderable' => false,
                'type_of_field' => 'integer',
            ))
            ->add('amendmentStatus', HumanizeTextColumnDeprecated::class, array(
                'title' => 'Status',
            ));

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'BudgetBundle\Entity\BudgetIncomeSummary';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'prebudgetsummary_datatable';
    }
}
