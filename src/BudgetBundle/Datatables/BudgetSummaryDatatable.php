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
class BudgetSummaryDatatable extends BaseDatatable
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

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('budget_summary_list')]));

        $this->addActionButton('budget_summary_update', 'Edit', 'glyphicon-edit', ['id' => 'id'], function ($row) {
            return $this->authorizationChecker->isGranted('ROLE_BUDGET_CLERK') && HumanizeTextColumnDeprecated::reformStatusValue($row['budgetStatus']) == 'draft';
        });
        $this->addActionButton('budget_summary_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);
        $this->addActionButton('budget_allocation', 'Edit Sanction', 'glyphicon-eye-open', ['id' => 'id'], function($row) {
            return $this->authorizationChecker->isGranted('ROLE_BUDGET_CLERK') && HumanizeTextColumnDeprecated::reformStatusValue($row['budgetStatus']) == 'allocation_wait_for_clerk';
        });
        $this->addActionButton('budget_allocation_view', 'View Sanction', 'glyphicon-eye-open', ['id' => 'id'], function($row) {
            return $this->canView('allocation', $row['budgetStatus']);
        });
        $this->addActionButton('budget_distribution', 'Edit Distribution', 'glyphicon-eye-open', ['id' => 'id'], function ($row) {
            return $this->authorizationChecker->isGranted('ROLE_BUDGET_CLERK') && HumanizeTextColumnDeprecated::reformStatusValue($row['budgetStatus']) == 'distribution_wait_for_clerk';
        });
        $this->addActionButton('budget_distribution_view', 'View Distribution', 'glyphicon-eye-open', ['id' => 'id'], function($row) {
            return $this->canView('distribution', $row['budgetStatus']);
        });

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
                        FROM BudgetBundle:BudgetSummaryDetail {budgetDetail} 
                        WHERE {budgetDetail}.budgetSummary = {budgetsummary}.id GROUP BY {budgetDetail}.budgetSummary)",
                'searchable' => false,
                'orderable' => false,
                'type_of_field' => 'integer',
            ))
            ->add('amount', NumberColumn::class, array(
                'formatter' => $this->currencyFormatter,
                'class_name' => 'text-right',
                'title' => 'Sanction Amount',
                'dql' => "(
                        SELECT SUM({budgetDetail2}.sanctionAmount) 
                        FROM BudgetBundle:BudgetSummaryDetail {budgetDetail2} 
                        WHERE {budgetDetail2}.budgetSummary = {budgetsummary}.id GROUP BY {budgetDetail2}.budgetSummary)",
                'searchable' => false,
                'orderable' => false,
                'type_of_field' => 'integer',
            ))
            ->add('budgetStatus', HumanizeTextColumnDeprecated::class, array(
                'title' => 'Status',
            ));

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'BudgetBundle\Entity\BudgetSummary';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'budgetsummary_datatable';
    }
}
