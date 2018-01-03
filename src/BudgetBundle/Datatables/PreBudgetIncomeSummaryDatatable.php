<?php
namespace BudgetBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use BudgetBundle\Datatables\Column\FinancialYearColumn;
use BudgetBundle\Datatables\Column\HumanizeTextColumnDeprecated;
use BudgetBundle\Entity\BudgetSummary;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\NumberColumn;

/**
 * Class BudgetSummaryDatatable
 *
 * @package BudgetBundle\Datatables
 */
class PreBudgetIncomeSummaryDatatable extends BaseDatatable
{
    static public function canView($type, $status)
    {
        $status = HumanizeTextColumnDeprecated::reformStatusValue($status);
        switch ($type) {
            case 'allocation':
                return in_array($status, ['allocation_wait_for_clerk','allocation_wait_for_head_clerk','allocation_wait_for_hc_rejection','allocation_wait_for_ao','allocation_wait_for_dd','allocation_wait_for_director','distribution_wait_for_clerk','distribution_wait_for_head_clerk','distribution_wait_for_hc_rejection','distribution_wait_for_ao','distribution_wait_for_dd','distribution_wait_for_director','completed']);
                break;
            case 'distribution':
            default:
                return in_array($status, ['distribution_wait_for_clerk','distribution_wait_for_head_clerk','distribution_wait_for_hc_rejection','distribution_wait_for_ao','distribution_wait_for_dd','distribution_wait_for_director','completed']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'dropdown';

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('pre_budget_income_summary_list')]));

        $this->addActionButton('pre_budget_income_summary_update', 'Edit', 'glyphicon-edit', ['id' => 'id'], function ($row) {
            return $this->authorizationChecker->isGranted('ROLE_BUDGET_CLERK') && HumanizeTextColumnDeprecated::reformStatusValue($row['status']) == 'draft';
        });
        $this->addActionButton('pre_budget_income_summary_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder
            ->add('financialYear.id', FinancialYearColumn::class, array(
                'title' => 'Financial Year',
            ))
            ->add('requestAmount', NumberColumn::class, array(
                'formatter' => $this->currencyFormatter,
                'class_name' => 'text-right',
                'title' => 'Request Amount',
                'dql' => "(
                        SELECT SUM({budgetDetail}.requestAmount) 
                        FROM BudgetBundle:PreBudgetIncomeSummaryDetail {budgetDetail} 
                        WHERE {budgetDetail}.budgetSummary = {prebudgetincomesummary}.id GROUP BY {budgetDetail}.budgetSummary)",
                'searchable' => false,
                'orderable' => false,
                'type_of_field' => 'integer',
            ))
            ->add('nextYearProjectionAmount', NumberColumn::class, array(
                'formatter' => $this->currencyFormatter,
                'class_name' => 'text-right',
                'title' => 'Next Year Projection',
                'dql' => "(
                        SELECT SUM({budgetDetail2}.nextYearProjectionAmount) 
                        FROM BudgetBundle:PreBudgetIncomeSummaryDetail {budgetDetail2} 
                        WHERE {budgetDetail2}.budgetSummary = {prebudgetincomesummary}.id GROUP BY {budgetDetail2}.budgetSummary)",
                'searchable' => false,
                'orderable' => false,
                'type_of_field' => 'integer',
            ))
            ->add('afterNextYearProjectionAmount', NumberColumn::class, array(
                'formatter' => $this->currencyFormatter,
                'class_name' => 'text-right',
                'title' => 'After Next Year Projection',
                'dql' => "(
                        SELECT SUM({budgetDetail3}.afterNextYearProjectionAmount) 
                        FROM BudgetBundle:PreBudgetIncomeSummaryDetail {budgetDetail3} 
                        WHERE {budgetDetail3}.budgetSummary = {prebudgetincomesummary}.id GROUP BY {budgetDetail3}.budgetSummary)",
                'searchable' => false,
                'orderable' => false,
                'type_of_field' => 'integer',
            ))
            ->add('status', HumanizeTextColumnDeprecated::class, array(
                'title' => 'Status',
            ));

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'BudgetBundle\Entity\PreBudgetIncomeSummary';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'prebudgetsummary_datatable';
    }
}
