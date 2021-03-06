<?php
namespace BudgetBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use BudgetBundle\Datatables\Column\FinancialYearColumn;
use BudgetBundle\Datatables\Column\HumanizeTextColumnDeprecated;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;

/**
 * Class BudgetIncomeDatatable
 *
 * @package BudgetBundle\Datatables
 */
class BudgetIncomeDatatable extends BaseDatatable
{
    public function getCustomLineFormatter()
    {
        $formatter = function($line ) {
            return $line;
        };

        return $formatter;

    }

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $budgetHeads = $this->em->getRepository('BudgetBundle:BudgetHead')->findAll();
        $financialYear = $this->em->getRepository('AppBundle:FinancialYear')->findAll();
        $this->actionButtonType = 'flat';

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('budget_income_list')]));

        $this->options->set($this->getDefaultOptions([
            'individual_filtering' => true,
            'order_cells_top' => true
        ]));

        $this->setDefaultExportButtons([0,1,2,4,5]);

        $this->addActionButton('budget_income_update', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row){
            return HumanizeTextColumnDeprecated::reformStatusValue($row['status']) == 'draft' && $this->authorizationChecker->isGranted(['ROLE_BUDGET_CLERK', 'ROLE_DASB_CLERK']);
        });
        $this->addActionButton('budget_income_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder
            ->add('financialYear.id', FinancialYearColumn::class, array(
                'title' => 'Financial Year',
                'filter' => array(SelectFilter::class, array(
                    'search_type' => 'eq',
                    'select_options' => array('' => 'All') + $this->getOptionsArrayFromEntities($financialYear, 'id', 'label'),
                ))
            ))
            ->add('budgetHead.titleBn', Column::class, array(
                'title' => 'Account Head',
                'filter' => array(SelectFilter::class, array(
                    'search_type' => 'eq',
                    'select_options' => array('' => 'All') + $this->getOptionsArrayFromEntities($budgetHeads, 'titleBn', 'label'),
                ))
            ))
            ->add('letterNo', Column::class, array(
                'title' => 'Letter No',
            ));

        $this->addDateColumn('letterDate', 'Letter Date');
        $this->addNumberColumn('amount', 'Amount');

        $this->columnBuilder->add('createdBy.username', Column::class, array(
                'title' => 'Entry By'
            ));

        $this->addDateColumn('createdAt', 'Entry Date');

        $this->columnBuilder->add('status', HumanizeTextColumnDeprecated::class, array(
            'title' => 'Status'
        ));

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'BudgetBundle\Entity\BudgetIncome';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'budgetincome_datatable';
    }
}
