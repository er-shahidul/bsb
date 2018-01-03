<?php

namespace BudgetBundle\Validator\Constraints;

use BudgetBundle\Entity\BudgetExpense;
use BudgetBundle\Entity\BudgetHead;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckMaxExpenseValidator extends ConstraintValidator
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if ($this->isBudgetExceed($value, $constraint->budgetExpense)) {
            $this->context->addViolation($constraint->message, array('%string%' => $value));
        }
    }

    protected function isBudgetExceed($value, BudgetExpense $budgetExpense)
    {
        $office = $budgetExpense->getOffice();
        $budget = $this->em->getRepository('BudgetBundle:Budget')->getCurrentYearBudget($office);
        $budgetHead = $budgetExpense->getBudgetHead();
        $expense = (float)$this->em->getRepository('BudgetBundle:BudgetExpenseSanction')->getTotalExpenseOfHead($budgetHead, $budget->getFinancialYear(), $office);
        $budget = (float)$this->em->getRepository('BudgetBundle:BudgetDetail')->getBudgetAmountOfHead($budget, $budgetHead);

        return (float)$value > ($budget - $expense);
    }

}