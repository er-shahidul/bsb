<?php

namespace BudgetBundle\Repository;

use AppBundle\Entity\Office;
use AppBundle\Repository\BaseRepository;
use BudgetBundle\Entity\BudgetExpense;
use BudgetBundle\Entity\BudgetHead;

/**
 * BudgetExpenseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BudgetExpenseRepository extends BaseRepository
{
    public function getTotalExpenseOfHead(BudgetHead $budgetHead, Office $office = null)
    {
        $qb = $this->createQueryBuilder('e');

        $qb->select('SUM(e.totalAmount) AS amount');
        $qb->where($qb->expr()->eq('e.budgetHead', $budgetHead->getId()));
        $qb->groupBy('e.budgetHead');
        if ($office) {
            $qb->addGroupBy('e.office');
            $qb->andWhere($qb->expr()->eq('e.office', $office->getId()));
        }

        if ($result = $qb->getQuery()->getOneOrNullResult()) {
            return (float)$result['amount'];
        }

        return 0;
    }
}
