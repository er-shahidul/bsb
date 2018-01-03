<?php
namespace BudgetBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use BudgetBundle\Datatables\Column\FinancialYearColumn;
use BudgetBundle\Datatables\Column\HumanizeTextColumnDeprecated;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

/**
 * Class BudgetDatatable
 *
 * @package BudgetBundle\Datatables
 */
class FundDatatable extends BaseDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($line){
            $total = $this->em->getRepository('BudgetBundle:BudgetDetail')->getTotalAmount($line['id']);
            $line['totalAmount'] = number_format($total['requestAmount'], 2);

            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'dropdown';

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('budget_fund_request_list')]));

        if ($this->authorizationChecker->isGranted(['ROLE_DASB_CLERK', 'ROLE_BUDGET_CLERK'])) {
            $this->addActionButton('budget_fund_request_update', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
                return HumanizeTextColumnDeprecated::reformStatusValue($row['status']) == 'draft' || empty($row['status']);
            });
        }
        $this->addActionButton('budget_fund_request_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder
            ->add('financialYear.id', FinancialYearColumn::class, array(
                'title' => 'Financial Year',
            ))
            ->add('totalAmount', VirtualColumn::class, array(
                'title' => 'Total Amount',
                'class_name' => 'text-right',

            ))
            ->add('status', HumanizeTextColumnDeprecated::class, array(
                'title' => 'Status'
            ))
        ;

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'BudgetBundle\Entity\FundRequest';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'budget_funddatatable';
    }
}
