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
class BudgetAmendmentDatatable extends BaseDatatable
{
    static public function canView($type, $status)
    {
        $status = HumanizeTextColumnDeprecated::reformStatusValue($status);
        switch ($type) {
            case 'amendmentsanction':
            default:
                return in_array($status, ['amendmentsanction_wait_for_clerk','amendmentsanction_wait_for_head_clerk','amendmentsanction_wait_for_hc_rejection','amendmentsanction_wait_for_ao','amendmentsanction_wait_for_dd','amendmentsanction_wait_for_director','completed']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'dropdown';

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('budget_amendment_list')]));

        $this->addActionButton('budget_amendment_update', 'Edit Request', 'glyphicon-edit', ['id' => 'id'], function ($row) {
            return $this->authorizationChecker->isGranted('ROLE_BUDGET_CLERK') && HumanizeTextColumnDeprecated::reformStatusValue($row['amendmentStatus']) == 'draft';
        });
        $this->addActionButton('budget_amendment_view', 'View Request', 'glyphicon-eye-open', ['id' => 'id']);

        $this->addActionButton('budget_amendment_sanction_update', 'Edit Sanction', 'glyphicon-edit', ['id' => 'id'], function ($row) {
            return $this->authorizationChecker->isGranted('ROLE_BUDGET_CLERK') && HumanizeTextColumnDeprecated::reformStatusValue($row['amendmentStatus']) == 'amendmentsanction_wait_for_clerk';
        });
        $this->addActionButton('budget_amendment_sanction_view', 'View Sanction', 'glyphicon-eye-open', ['id' => 'id'], function($row){
            return $this->authorizationChecker->isGranted('ROLE_BUDGET_CLERK') && $this->canView('amendmentsanction', $row['status']);
        });


        $this->columnBuilder
            ->add('financialYear.id', FinancialYearColumn::class, array(
                'title' => 'Financial Year',
            ))
            ->add('sanctionAmount', NumberColumn::class, array(
                'formatter' => $this->currencyFormatter,
                'class_name' => 'text-right',
                'title' => 'Original Amount',
                'dql' => "(
                        SELECT SUM({budgetDetail3}.sanctionAmount) 
                        FROM BudgetBundle:BudgetSummaryDetail {budgetDetail3} 
                        WHERE {budgetDetail3}.budgetSummary = {budgetsummary}.id GROUP BY {budgetDetail3}.budgetSummary)",
                'searchable' => false,
                'orderable' => false,
                'type_of_field' => 'integer',
            ))
            ->add('requestAmount', NumberColumn::class, array(
                'formatter' => $this->currencyFormatter,
                'class_name' => 'text-right',
                'title' => 'Request Revised Amount',
                'dql' => "(
                        SELECT SUM({budgetDetail}.amendmentRequestAmount) 
                        FROM BudgetBundle:BudgetSummaryDetail {budgetDetail} 
                        WHERE {budgetDetail}.budgetSummary = {budgetsummary}.id GROUP BY {budgetDetail}.budgetSummary)",
                'searchable' => false,
                'orderable' => false,
                'type_of_field' => 'integer',
            ))
            ->add('amount', NumberColumn::class, array(
                'title' => 'Revised Sanction Amount',
                'formatter' => $this->currencyFormatter,
                'class_name' => 'text-right',
                'dql' => "(
                        SELECT SUM({budgetDetail2}.amendmentSanctionAmount) 
                        FROM BudgetBundle:BudgetSummaryDetail {budgetDetail2} 
                        WHERE {budgetDetail2}.budgetSummary = {budgetsummary}.id GROUP BY {budgetDetail2}.budgetSummary)",
                'searchable' => false,
                'orderable' => false,
                'type_of_field' => 'integer',
            ))
            ->add('amendmentStatus', HumanizeTextColumnDeprecated::class, array(
                'title' => 'Status',
            ))
            ->add('status', Column::class, array(
                'visible' => false,
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
        return 'budgetamendment_datatable';
    }
}
