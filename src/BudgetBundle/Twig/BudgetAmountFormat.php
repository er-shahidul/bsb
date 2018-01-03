<?php

namespace BudgetBundle\Twig;

use BudgetBundle\Entity\Budget;

class BudgetAmountFormat extends \Twig_Extension
{

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('budgetExpenseAmount', [$this, 'budgetExpenseAmount']),
            new \Twig_SimpleFilter('budgetAmount', [$this, 'budgetAmount']),
        ];
    }

    function budgetExpenseAmount($amount, $encode = false) {
        if (twig_test_empty($amount)) {
            return 0;
        }

        if (!$amount) {
            $amount = 0;
        }

        if ($encode) {
            $amount = Budget::encodeAmount($amount);
        }

        return number_format($amount, 2);
    }

    function budgetAmount($amount, $decode = false) {
        if (!$amount) {
            $amount = 0;
        }

        if ($decode) {
            $amount = Budget::decodeAmount($amount);
        }

        return number_format($amount, 2);
    }

}
