<?php

namespace BudgetBundle\Twig;

use BudgetBundle\Entity\Budget;

class BudgetAmountEncodeDecode extends \Twig_Extension
{

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('encodeBudgetAmount', [$this, 'encodeBudgetAmount']),
            new \Twig_SimpleFilter('decodeBudgetAmount', [$this, 'decodeBudgetAmount']),
        ];
    }

    function encodeBudgetAmount($amount) {
        if (empty($amount) || !is_numeric($amount)) {
            return $amount;
        }
        return Budget::encodeAmount($amount);
    }

    function decodeBudgetAmount($amount) {
        if (empty($amount) || !is_numeric($amount)) {
            return $amount;
        }
        return Budget::decodeAmount($amount);
    }

}
