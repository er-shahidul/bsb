<?php

namespace BudgetBundle\Repository;

use AppBundle\Entity\FinancialYear;
use AppBundle\Entity\Office;
use AppBundle\Repository\BaseRepository;
use AppBundle\Utility\DateUtil;
use BudgetBundle\Entity\Budget;
use BudgetBundle\Entity\BudgetDetail;
use BudgetBundle\Entity\FundRequest;
use Monolog\Handler\Curl\Util;

/**
 * BudgetRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FundRequestRepository extends BaseBudgetRepository
{
    public function createFundRequest($data, $financialYear, $office)
    {
        $budget = new FundRequest();
        $budget->setFinancialYear($financialYear);
        $budget->setOffice($office);

        $this->_em->persist($budget);
        $this->_em->flush();

        $this->prepareFundRequest($budget, $data);

        return $budget;
    }

    public function updateFundRequest(FundRequest $budget, $data)
    {
        foreach ($budget->getBudgetDetails() as $budgetDetail) {
            $this->_em->remove($budgetDetail);
        }
        $this->_em->flush();

        $this->prepareFundRequest($budget, $data);
    }

    public function prepareFundRequest(FundRequest $budget, $data)
    {
        foreach ($data as $budgetHeadId => $row) {
            $budgetHead = $this->_em->getRepository('BudgetBundle:BudgetHead')->find($budgetHeadId);

            if (!$budgetHead) {
                continue;
            }

            $budgetDetail = new BudgetDetail();
            $budgetDetail->setBudget($budget);
            $budgetDetail->setBudgetHead($budgetHead);
            $budgetDetail->setRequestAmount((float)$row['requestAmount']);
            $budgetDetail->setRemark($row['remark']);
            $this->_em->persist($budgetDetail);
        }

        $this->_em->flush();
    }

    public function updateFundRequestAllocation($data)
    {
        foreach ($data as $budgetDetailId => $row) {
            $budgetDetail = $this->_em->getRepository('BudgetBundle:BudgetDetail')->find($budgetDetailId);

            if (!$budgetDetail) {
                continue;
            }

            $budgetDetail->setAmount((float)$row['amount']);
            $this->_em->persist($budgetDetail);
        }

        $this->_em->flush();
    }
}
