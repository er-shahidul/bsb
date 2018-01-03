<?php
namespace BudgetBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

/**
 * Class BudgetDatatable
 *
 * @package BudgetBundle\Datatables
 */
class BudgetDatatable extends BaseDatatable
{
    public function getCustomLineFormatter()
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
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('budget_list')]));

        if ($this->authorizationChecker->isGranted(['ROLE_DASB_CLERK', 'ROLE_BUDGET_CLERK'])) {
            $this->addActionButton('budget_update', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
                return $row['status'] == 'draft' || empty($row['status']);
            });
        }
        $this->addActionButton('budget_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->addFinancialYearColumn('financialYear', 'Financial Year');
        $this->columnBuilder
            ->add('totalAmount', VirtualColumn::class, array(
                'title' => 'Total Amount'
            ));
        $this->addStatusColumn('status', 'Status');

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'BudgetBundle\Entity\Budget';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'budget_datatable';
    }
}
