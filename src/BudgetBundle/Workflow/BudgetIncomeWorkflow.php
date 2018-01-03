<?php

namespace BudgetBundle\Workflow;


use AppBundle\Workflow\GenericWorkflow;
use BudgetBundle\Entity\BudgetIncome;

class BudgetIncomeWorkflow extends GenericWorkflow
{
    public static function getSupports()
    {
        return [BudgetIncome::class];
    }

    public static function getName()
    {
        return 'budget_income';
    }

    public static function getTransitionsConfig()
    {
        return parent::getTransitionsConfig();
    }

    public static function getEditablePlaces()
    {
        return [
          'draft'
        ];
    }
}