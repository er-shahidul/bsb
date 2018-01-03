<?php

namespace BudgetBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class CheckMaxExpense extends Constraint
{
    public $message = 'Amount should not exceed budget';
    public $budgetExpense;

}