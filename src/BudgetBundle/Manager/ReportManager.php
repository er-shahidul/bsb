<?php

namespace BudgetBundle\Manager;

use AppBundle\Entity\FinancialYear;
use AppBundle\Utility\DateUtil;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class ReportManager
{
    /** @var  ObjectManager */
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getQuarterWiseExpense($financialYear, $type)
    {
        $budgetSanctionRepo = $this->em->getRepository('BudgetBundle:BudgetExpenseSanction');
        $budgetDetailRepo = $this->em->getRepository('BudgetBundle:BudgetSummaryDetail');
        $dateRange = DateUtil::getFYQuarterDateRange(new \DateTime($financialYear));
        $expenseData['budgetHead'] = $this->em->getRepository('BudgetBundle:BudgetHead')->getParentBudgetHead();

        $expenseData['parent']['budgetInfo'] = $budgetDetailRepo->getTotalAmountParentHeadWise($financialYear, 'budgetStatus');
        $expenseData['parent']['firstQuarter']  = $budgetSanctionRepo->getAllParentHeadTotal($financialYear, $dateRange['first']);
        $expenseData['parent']['secondQuarter'] = $budgetSanctionRepo->getAllParentHeadTotal($financialYear, $dateRange['second']);
        $expenseData['parent']['thirdQuarter']  = $budgetSanctionRepo->getAllParentHeadTotal($financialYear, $dateRange['third']);
        $expenseData['parent']['fourthQuarter'] = $budgetSanctionRepo->getAllParentHeadTotal($financialYear, $dateRange['forth']);

        if ($type == 'child-head') {
            $expenseData['child']['budgetInfo'] = $budgetDetailRepo->getAmounts($financialYear, 'budgetStatus');
            $expenseData['child']['firstQuarter']  = $budgetSanctionRepo->getAllHeadTotal($financialYear, null, $dateRange['first']);
            $expenseData['child']['secondQuarter'] = $budgetSanctionRepo->getAllHeadTotal($financialYear, null, $dateRange['second']);
            $expenseData['child']['thirdQuarter']  = $budgetSanctionRepo->getAllHeadTotal($financialYear, null, $dateRange['third']);
            $expenseData['child']['fourthQuarter'] = $budgetSanctionRepo->getAllHeadTotal($financialYear, null, $dateRange['forth']);
        }

        $expenseData['total']['firstQuarter'] = array_sum($expenseData['parent']['firstQuarter']);
        $expenseData['total']['secondQuarter'] = array_sum($expenseData['parent']['secondQuarter']);
        $expenseData['total']['thirdQuarter'] = array_sum($expenseData['parent']['thirdQuarter']);
        $expenseData['total']['fourthQuarter'] = array_sum($expenseData['parent']['fourthQuarter']);
        $expenseData['total']['totalExpense'] = array_sum($expenseData['total']);

        return $expenseData;
    }

    public function calculateQuarterWiseExpenseTotal(&$data)
    {
        $data['firstQuarterTotal']=0;
        $data['secondQuarterTotal']=0;
        $data['thirdQuarterTotal']=0;
        $data['fourthQuarterTotal']=0;

        if(isset( $data['budgetHead'])){
            foreach ($data['budgetHead'] as $parentHead){
                if(isset($data['reportData']['parent'])){
                    $data['firstQuarterTotal']+=  $data['reportData']['parent'][$parentHead->getId().'-first'];
                    $data['secondQuarterTotal']+=  $data['reportData']['parent'][$parentHead->getId().'-second'];
                    $data['thirdQuarterTotal']+=  $data['reportData']['parent'][$parentHead->getId().'-third'];
                    $data['fourthQuarterTotal']+=  $data['reportData']['parent'][$parentHead->getId().'-fourth'];
                }
            }
        }
    }

    public function getQuarterWiseIncome($financialYear, $type)
    {
        $budgetSanctionRepo = $this->em->getRepository('BudgetBundle:BudgetExpenseSanction');
        $budgetDetailRepo = $this->em->getRepository('BudgetBundle:BudgetIncomeSummaryDetail');
        $dateRange = DateUtil::getFYQuarterDateRange(new \DateTime($financialYear));
        $incomeData['budgetHead'] = $this->em->getRepository('BudgetBundle:BudgetIncomeHead')->getParentBudgetHead();

        $incomeData['parent']['budgetInfo'] = $budgetDetailRepo->getTotalAmountParentHeadWise($financialYear, 'budgetStatus');
        $incomeData['parent']['firstQuarter']  = $budgetSanctionRepo->getAllParentHeadTotal($financialYear, $dateRange['first']);
        $incomeData['parent']['secondQuarter'] = $budgetSanctionRepo->getAllParentHeadTotal($financialYear, $dateRange['second']);
        $incomeData['parent']['thirdQuarter']  = $budgetSanctionRepo->getAllParentHeadTotal($financialYear, $dateRange['third']);
        $incomeData['parent']['fourthQuarter'] = $budgetSanctionRepo->getAllParentHeadTotal($financialYear, $dateRange['forth']);

        if ($type == 'child-head') {
            $incomeData['child']['budgetInfo'] = $budgetDetailRepo->getAmounts($financialYear, 'budgetStatus');
            $incomeData['child']['firstQuarter']  = $budgetSanctionRepo->getAllHeadTotal($financialYear, null, $dateRange['first']);
            $incomeData['child']['secondQuarter'] = $budgetSanctionRepo->getAllHeadTotal($financialYear, null, $dateRange['second']);
            $incomeData['child']['thirdQuarter']  = $budgetSanctionRepo->getAllHeadTotal($financialYear, null, $dateRange['third']);
            $incomeData['child']['fourthQuarter'] = $budgetSanctionRepo->getAllHeadTotal($financialYear, null, $dateRange['forth']);
        }

        $incomeData['total']['firstQuarter'] = array_sum($incomeData['parent']['firstQuarter']);
        $incomeData['total']['secondQuarter'] = array_sum($incomeData['parent']['secondQuarter']);
        $incomeData['total']['thirdQuarter'] = array_sum($incomeData['parent']['thirdQuarter']);
        $incomeData['total']['fourthQuarter'] = array_sum($incomeData['parent']['fourthQuarter']);
        $incomeData['total']['totalExpense'] = array_sum($incomeData['total']);

        return $incomeData;
    }

    public function getExpenseTillMonthData($financialYear, $month, $office)
    {
        $budgetSanctionRepo = $this->em->getRepository('BudgetBundle:BudgetExpenseSanction');
        $dateRange = DateUtil::getFYDateRange($financialYear);
        $startDate = new \DateTime($financialYear.'-'.$month.'-01');
        $endDate = DateUtil::roundDate(new \DateTime(date('Y-m-t', strtotime($financialYear.'-'.$month.'-01'))));

        return [
            'month' => $budgetSanctionRepo->getAllHeadTotal($financialYear, $office, ['start' => $startDate, 'end' => $endDate]),
            'total' => $budgetSanctionRepo->getAllHeadTotal($financialYear, $office, ['start' => $dateRange['start'], 'end' => $endDate]),
            'subMonth' => $budgetSanctionRepo->getAllParentHeadTotal($financialYear, ['start' => $startDate, 'end' => $endDate], false, $office),
            'subTotal' => $budgetSanctionRepo->getAllParentHeadTotal($financialYear, ['start' => $dateRange['start'], 'end' => $endDate], false, $office),
            'grantMonth' => $budgetSanctionRepo->getAllParentHeadTotal($financialYear, ['start' => $startDate, 'end' => $endDate], true, $office),
            'grandTotal' => $budgetSanctionRepo->getAllParentHeadTotal($financialYear, ['start' => $dateRange['start'], 'end' => $endDate], true, $office),
        ];
    }

    public function getAllExpenseTillMonthData($financialYear, $month, $office)
    {
        $budgetSanctionRepo = $this->em->getRepository('BudgetBundle:BudgetExpenseSanction');
        $dateRange = DateUtil::getFYDateRange($financialYear);
        $startDate = new \DateTime($financialYear.'-'.$month.'-01');
        $endDate = DateUtil::roundDate(new \DateTime(date('Y-m-t', strtotime($financialYear.'-'.$month.'-01'))));

        return [
            'month' => $budgetSanctionRepo->getAllHeadAllTotal($financialYear, $office, ['start' => $startDate, 'end' => $endDate]),
            'total' => $budgetSanctionRepo->getAllHeadTotal($financialYear, $office, ['start' => $dateRange['start'], 'end' => $endDate]),
            'subMonth' => $budgetSanctionRepo->getAllParentHeadAllTotal($financialYear, ['start' => $startDate, 'end' => $endDate], false, $office),
            'subTotal' => $budgetSanctionRepo->getAllParentHeadTotal($financialYear, ['start' => $dateRange['start'], 'end' => $endDate], false, $office),
            'grantMonth' => $budgetSanctionRepo->getAllParentHeadTotal($financialYear, ['start' => $startDate, 'end' => $endDate], true, $office),
            'grandTotal' => $budgetSanctionRepo->getAllParentHeadTotal($financialYear, ['start' => $dateRange['start'], 'end' => $endDate], true, $office),
        ];
    }

    public function getBudgetRequestExpenseData($financialYear)
    {
        $budgetDetailRepo = $this->em->getRepository('BudgetBundle:BudgetSummaryDetail');

        $data['budgetInfo']['head'] = $budgetDetailRepo->getAmounts($financialYear, 'budgetStatus');
        $data['budgetInfo']['parentHead'] = $budgetDetailRepo->getTotalAmountParentHeadWise($financialYear, 'budgetStatus');
        $data['budgetInfo']['totalAmount'] = $budgetDetailRepo->getTotalAmountOfYear($financialYear, 'budgetStatus');

        $data['prevBudgetInfo']['head'] = $budgetDetailRepo->getAmounts($financialYear - 1, 'budgetStatus');
        $data['prevBudgetInfo']['parentHead'] = $budgetDetailRepo->getTotalAmountParentHeadWise($financialYear - 1, 'budgetStatus');
        $data['prevBudgetInfo']['totalAmount'] = $budgetDetailRepo->getTotalAmountOfYear($financialYear - 1, 'budgetStatus');

        $data['expenses']['beforePrev'] = $this->getExpenseDataOfYear($financialYear - 3);
        $data['expenses']['prev'] = $this->getExpenseDataOfYear($financialYear - 2);

        return $data;
    }

    public function getPreBudgetExpenseData($financialYear)
    {
        $budgetDetailRepo = $this->em->getRepository('BudgetBundle:PreBudgetSummaryDetail');

        $data['budgetInfo']['head'] = $budgetDetailRepo->getAmounts($financialYear, 'status');
        $data['budgetInfo']['parentHead'] = $budgetDetailRepo->getTotalAmountParentHeadWise($financialYear, 'status');
        $data['budgetInfo']['totalAmount'] = $budgetDetailRepo->getTotalAmountOfYear($financialYear, 'status');

        $data['prevBudgetInfo']['head'] = $budgetDetailRepo->getAmounts($financialYear - 1, 'status');
        $data['prevBudgetInfo']['parentHead'] = $budgetDetailRepo->getTotalAmountParentHeadWise($financialYear - 1, 'status');
        $data['prevBudgetInfo']['totalAmount'] = $budgetDetailRepo->getTotalAmountOfYear($financialYear - 1, 'status');

        $data['expenses']['beforePrev'] = $this->getExpenseDataOfYear($financialYear - 3);
        $data['expenses']['prev'] = $this->getExpenseDataOfYear($financialYear - 2);

        return $data;
    }

    public function getBudgetRequestIncomeData($financialYear)
    {
        $budgetIncomeDetailRepo = $this->em->getRepository('BudgetBundle:BudgetIncomeSummaryDetail');

        $data['budgetInfo']['head'] = $budgetIncomeDetailRepo->getAmounts($financialYear, 'budgetStatus');
        $data['budgetInfo']['parentHead'] = $budgetIncomeDetailRepo->getTotalAmountParentHeadWise($financialYear, 'budgetStatus');
        $data['budgetInfo']['totalAmount'] = $budgetIncomeDetailRepo->getTotalAmountOfYear($financialYear, 'budgetStatus');

        $data['prevBudgetInfo']['head'] = $budgetIncomeDetailRepo->getAmounts($financialYear - 1, 'budgetStatus');
        $data['prevBudgetInfo']['parentHead'] = $budgetIncomeDetailRepo->getTotalAmountParentHeadWise($financialYear - 1, 'budgetStatus');
        $data['prevBudgetInfo']['totalAmount'] = $budgetIncomeDetailRepo->getTotalAmountOfYear($financialYear - 1, 'budgetStatus');

        $data['expenses']['beforePrev'] = $this->getIncomeDataOfYear($financialYear - 3);
        $data['expenses']['prev'] = $this->getIncomeDataOfYear($financialYear - 2);

        return $data;
    }

    public function getPreBudgetIncomeData($financialYear)
    {
        $budgetIncomeDetailRepo = $this->em->getRepository('BudgetBundle:PreBudgetIncomeSummaryDetail');

        $data['budgetInfo']['head'] = $budgetIncomeDetailRepo->getAmounts($financialYear, 'status');
        $data['budgetInfo']['parentHead'] = $budgetIncomeDetailRepo->getTotalAmountParentHeadWise($financialYear, 'status');
        $data['budgetInfo']['totalAmount'] = $budgetIncomeDetailRepo->getTotalAmountOfYear($financialYear, 'status');

        $data['prevBudgetInfo']['head'] = $budgetIncomeDetailRepo->getAmounts($financialYear - 1, 'status');
        $data['prevBudgetInfo']['parentHead'] = $budgetIncomeDetailRepo->getTotalAmountParentHeadWise($financialYear - 1, 'status');
        $data['prevBudgetInfo']['totalAmount'] = $budgetIncomeDetailRepo->getTotalAmountOfYear($financialYear - 1, 'status');

        $data['expenses']['beforePrev'] = $this->getExpenseDataOfYear($financialYear - 3);
        $data['expenses']['prev'] = $this->getExpenseDataOfYear($financialYear - 2);

        return $data;
    }

    protected function getExpenseDataOfYear($financialYear, $dateRange = [])
    {
        $budgetSanctionRepo = $this->em->getRepository('BudgetBundle:BudgetExpenseSanction');
        if (empty($dateRange)) {
            $dateRange = DateUtil::getFYDateRange($financialYear);
        }

        return [
            'total' => $budgetSanctionRepo->getAllHeadTotal($financialYear, null, ['start' => $dateRange['start'], 'end' => $dateRange['end']]),
            'subTotal' => $budgetSanctionRepo->getAllParentHeadTotal($financialYear, ['start' => $dateRange['start'], 'end' => $dateRange['end']]),
            'grandTotal' => $budgetSanctionRepo->getAllParentHeadTotal($financialYear, ['start' => $dateRange['start'], 'end' => $dateRange['end']], true),
        ];
    }

    public function getSurrenderExpenseData($financialYear)
    {
        $budgetSanctionRepo = $this->em->getRepository('BudgetBundle:BudgetExpenseSanction');
        $dateRange = DateUtil::getFYDateRange($financialYear);

        return [
            'total' => $budgetSanctionRepo->getAllHeadTotal($financialYear, null, ['start' => $dateRange['start'], 'end' => $dateRange['end']]),
            'subTotal' => $budgetSanctionRepo->getAllParentHeadTotal($financialYear, ['start' => $dateRange['start'], 'end' => $dateRange['end']]),
            'grandTotal' => $budgetSanctionRepo->getAllParentHeadTotal($financialYear, ['start' => $dateRange['start'], 'end' => $dateRange['end']], true),
        ];
    }

    public function getBudgetAmendmentData($financialYear)
    {
        $budgetDetailRepo = $this->em->getRepository('BudgetBundle:BudgetSummaryDetail');

        $data['budgetInfo']['head'] = $budgetDetailRepo->getAmounts($financialYear, 'budgetStatus');
        $data['budgetInfo']['parentHead'] = $budgetDetailRepo->getTotalAmountParentHeadWise($financialYear, 'budgetStatus');
        $data['budgetInfo']['totalAmount'] = $budgetDetailRepo->getTotalAmountOfYear($financialYear, 'budgetStatus');

        $data['amendmentInfo']['head'] = $budgetDetailRepo->getAmounts($financialYear, 'amendmentStatus');
        $data['amendmentInfo']['parentHead'] = $budgetDetailRepo->getTotalAmountParentHeadWise($financialYear, 'amendmentStatus');
        $data['amendmentInfo']['totalAmount'] = $budgetDetailRepo->getTotalAmountOfYear($financialYear, 'amendmentStatus');

        $dateRange = DateUtil::getFYQuarterDateRange($financialYear - 2);
        $data['expenses']['beforePrev'] = $this->getExpenseDataOfYear($financialYear - 2);
        $data['expenses']['beforePrev1stSixMonth'] = $this->getExpenseDataOfYear($financialYear - 2, ['start' => $dateRange['first']['start'], 'end' => $dateRange['second']['end']]);

        $dateRange = DateUtil::getFYQuarterDateRange($financialYear - 1);
        $data['expenses']['prev'] = $this->getExpenseDataOfYear($financialYear - 1);
        $data['expenses']['prev1stSixMonth'] = $this->getExpenseDataOfYear($financialYear - 1, ['start' => $dateRange['first']['start'], 'end' => $dateRange['second']['end']]);

        $dateRange = DateUtil::getFYQuarterDateRange($financialYear);
        $data['expenses']['current'] = $this->getExpenseDataOfYear($financialYear, ['start' => $dateRange['first']['start'], 'end' => $dateRange['first']['end']]);

        return $data;
    }

    protected function getIncomeDataOfYear($financialYear, $dateRange = [])
    {
        $budgetIncomeSummaryDetailRepo = $this->em->getRepository('BudgetBundle:BudgetIncome');
        if (empty($dateRange)) {
            $dateRange = DateUtil::getFYDateRange($financialYear);
        }

        return [
            'total' => $budgetIncomeSummaryDetailRepo->getAllHeadTotal($financialYear, null, ['start' => $dateRange['start'], 'end' => $dateRange['end']]),
            'subTotal' => $budgetIncomeSummaryDetailRepo->getAllParentHeadTotal($financialYear, ['start' => $dateRange['start'], 'end' => $dateRange['end']]),
            'grandTotal' => $budgetIncomeSummaryDetailRepo->getAllParentHeadTotal($financialYear, ['start' => $dateRange['start'], 'end' => $dateRange['end']], true),
        ];
    }

    public function getBudgetIncomeAmendmentData($financialYear)
    {
        $budgetIncomeDetailRepo = $this->em->getRepository('BudgetBundle:BudgetIncomeSummaryDetail');

        $data['budgetInfo']['head'] = $budgetIncomeDetailRepo->getAmounts($financialYear, 'budgetStatus');
        $data['budgetInfo']['parentHead'] = $budgetIncomeDetailRepo->getTotalAmountParentHeadWise($financialYear, 'budgetStatus');
        $data['budgetInfo']['totalAmount'] = $budgetIncomeDetailRepo->getTotalAmountOfYear($financialYear, 'budgetStatus');

        $data['amendmentInfo']['head'] = $budgetIncomeDetailRepo->getAmounts($financialYear, 'amendmentStatus');
        $data['amendmentInfo']['parentHead'] = $budgetIncomeDetailRepo->getTotalAmountParentHeadWise($financialYear, 'amendmentStatus');
        $data['amendmentInfo']['totalAmount'] = $budgetIncomeDetailRepo->getTotalAmountOfYear($financialYear, 'amendmentStatus');

        $dateRange = DateUtil::getFYQuarterDateRange($financialYear - 2);
        $data['expenses']['beforePrev'] = $this->getIncomeDataOfYear($financialYear - 2);
        $data['expenses']['beforePrev1stSixMonth'] = $this->getIncomeDataOfYear($financialYear - 2, ['start' => $dateRange['first']['start'], 'end' => $dateRange['second']['end']]);

        $dateRange = DateUtil::getFYQuarterDateRange($financialYear - 1);
        $data['expenses']['prev'] = $this->getIncomeDataOfYear($financialYear - 1);
        $data['expenses']['prev1stSixMonth'] = $this->getIncomeDataOfYear($financialYear - 1, ['start' => $dateRange['first']['start'], 'end' => $dateRange['second']['end']]);

        $dateRange = DateUtil::getFYQuarterDateRange($financialYear);
        $data['expenses']['current'] = $this->getIncomeDataOfYear($financialYear, ['start' => $dateRange['first']['start'], 'end' => $dateRange['first']['end']]);

        return $data;
    }

    public function getOfficeExpensesData($data)
    {
        $hqOffice = $this->em->getRepository('AppBundle:Office')->getHQOffice();
        $expenseSanctionRepo = $this->em->getRepository('BudgetBundle:BudgetExpenseSanction');
        $budgetSummaryDetailRepo = $this->em->getRepository('BudgetBundle:BudgetSummaryDetail');

        $fyDateRange = DateUtil::getFYDateRange($data['year']);
        $dateRange = ['start' => $fyDateRange['start'], 'end' => DateUtil::roundDate(new \DateTime($data['date']))];

        $data['budgetAmount'] = $budgetSummaryDetailRepo->getBudgetSummaryAmountByYear($data['year']);
        $data['budgetParentHead'] = $budgetSummaryDetailRepo->getTotalAmountParentHeadWise($data['year']);
        $data['budgetTotalAmount'] = $budgetSummaryDetailRepo->getTotalAmountOfYear($data['year']);

        $data['officeExpense'] = $expenseSanctionRepo->getOfficeWiseExpenses($data['year'], 'child', $dateRange);
        $data['officeExpenseSubHead'] = $expenseSanctionRepo->getOfficeWiseExpenses($data['year'], 'parent', $dateRange);
        $data['officeExpenseTotal'] = $expenseSanctionRepo->getOfficeWiseExpenses($data['year'], null, $dateRange);

        $data['headExpense'] = $expenseSanctionRepo->getAllHeadTotal($data['year'], null, $dateRange);
        $data['headExpenseSubHead'] = $expenseSanctionRepo->getAllParentHeadTotal($data['year'], $dateRange);
        $data['headExpenseTotal'] = $expenseSanctionRepo->getAllParentHeadTotal($data['year'], $dateRange, true);

        $data['budgetHead'] = $this->em->getRepository('BudgetBundle:BudgetHead')->getParentBudgetHead();
        $data['offices'] = $this->em->getRepository('AppBundle:Office')->findAll();

        return $data;
    }

    public function getMonthlyExpensesData($data)
    {
        $expenseSanctionRepo = $this->em->getRepository('BudgetBundle:BudgetExpenseSanction');
        $budgetSummaryDetailRepo = $this->em->getRepository('BudgetBundle:BudgetSummaryDetail');

        $data['budgetHead'] = $this->em->getRepository('BudgetBundle:BudgetHead')->getParentBudgetHead();

        $data['budgetAmount'] = $budgetSummaryDetailRepo->getAmounts($data['year'], 'budgetStatus');
        $data['budgetTotalAmount'] = $budgetSummaryDetailRepo->getTotalAmountOfYear($data['year'], 'budgetStatus');

        $dateRange = DateUtil::getFYDateRange($data['year']);
        $data['headExpense'] = $expenseSanctionRepo->getAllHeadTotal($data['year'], null, $dateRange);
        $data['headExpenseTotal'] = $expenseSanctionRepo->getAllParentHeadTotal($data['year'], $dateRange, true);

        $startOfMonth = new \DateTime($data['year'].'-07-01');
        for ($i=1; $i <= 12; $i++){
            $endOfMonth = new \DateTime($startOfMonth->format('Y-m-t'));
            $data['monthsData'][$startOfMonth->format('Y-m-01')] = $expenseSanctionRepo->getAllHeadTotal($data['year'], null, ['start' => $startOfMonth, 'end' => $endOfMonth]);

            $startOfMonth->modify('+1 month');
        }

        $this->__calculateMonthTotal($data);
        return $data;
    }

    protected function __calculateMonthTotal(&$data)
    {
         foreach ($data['monthsData'] as $date => $row) {
             $data['monthsTotalData'][$date] = array_sum($data['monthsData'][$date]);
         }
    }
}