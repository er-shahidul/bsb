<?php

namespace BudgetBundle\Controller;

use AppBundle\Utility\DateUtil;
use BudgetBundle\Form\ReportForm\BudgetRequestReportForm;
use BudgetBundle\Form\ReportForm\BudgetSurrenderReportForm;
use BudgetBundle\Form\ReportForm\ExpenseOfMonthReportForm;
use BudgetBundle\Manager\ReportManager;
use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Libs\Mpdf\MpdfFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/report")
 */
class ReportController extends BudgetBaseController
{
    /**
     * @Route("/quarter-wise-expense", name="budget_report_quarterly_expense")
     */
    public function quarterWiseExpenseAction(Request $request)
    {
        $data['year'] = $request->query->get('year');
        $data['type'] = $request->query->get('type', 'child-head');
        $policyManager = $this->get(PolicyManager::class);

        $data['reportData'] = $policyManager->getPolicyJson('report_data.quarter_wise_budget.' . $data['year']);

        if ($data['year']) {
            $reportManager = $this->get(ReportManager::class);
            $data = $data + $reportManager->getQuarterWiseExpense($data['year'], $data['type']);
            $reportManager->calculateQuarterWiseExpenseTotal($data);
        }

        if (isset($_GET['print'])) {
            $mpdf = MpdfFactory::create();
            $html = $this->renderView('@Budget/Report/QuarterWise/quater-wise-pdf.html.twig', $data);
            $mpdf->WriteHTML($html);
            $mpdf->Output("Quater Wise Report - {$data['date']}.pdf", 'I');
        }
        if ($request->isMethod('POST')) {
            $new = array_merge($data['reportData'], $request->request->all());
            $policyManager->savePolicyJson('report_data.quarter_wise_budget.' . $data['year'], $new);

            $this->addFlash('success', 'Data Saved Successfully');

            return $this->redirectToRoute(
                'budget_report_quarterly_expense',
                ['year' => $data['year'], 'type' => $data['type']]
            );
        }

        return $this->render('@Budget/Report/QuarterWise/quater-wise.html.twig', $data);
    }

    /**
     * @Route("/pre-quarter-wise-expense", name="pre_budget_report_quarterly_expense")
     */
    public function quarterWiseExpenseForm2Action(Request $request)
    {
        $data['year'] = $request->query->get('year');
        $data['type'] = $request->query->get('type', 'child-head');
        $policyManager = $this->get(PolicyManager::class);

        $data['reportData'] = $policyManager->getPolicyJson('report_data.quarter_wise_budget.' . $data['year']);

        if ($data['year']) {
            $reportManager = $this->get(ReportManager::class);
            $data = $data + $reportManager->getQuarterWiseExpense($data['year'], $data['type']);
            $reportManager->calculateQuarterWiseExpenseTotal($data);
        }

        if (isset($_GET['print'])) {
            $mpdf = MpdfFactory::create();
            $html = $this->renderView('@Budget/Report/QuarterWise/quater-wise-pdf-form2.html.twig', $data);
            $mpdf->WriteHTML($html);
            $mpdf->Output("Quater Wise Report - {$data['date']}.pdf", 'I');
        }
        if ($request->isMethod('POST')) {
            $new = array_merge($data['reportData'], $request->request->all());
            $policyManager->savePolicyJson('report_data.quarter_wise_budget.' . $data['year'], $new);

            $this->addFlash('success', 'Data Saved Successfully');

            return $this->redirectToRoute(
                'pre_budget_report_quarterly_expense',
                ['year' => $data['year'], 'type' => $data['type']]
            );
        }

        return $this->render('@Budget/Report/QuarterWise/quater-wise-form2.html.twig', $data);
    }


    /**
     * @Route("/budget-quarter-wise-income", name="budget_report_quarterly_income")
     */
    public function quarterWiseIncomeAction(Request $request)
    {
        $data['year'] = $request->query->get('year');
        $data['type'] = $request->query->get('type', 'child-head');
        $policyManager = $this->get(PolicyManager::class);

        $data['reportData'] = $policyManager->getPolicyJson('report_data.quarter_wise_budget_income.' . $data['year']);
        if ($data['year']) {
            $reportManager = $this->get(ReportManager::class);
            $data = $data + $reportManager->getQuarterWiseIncome($data['year'], $data['type']);
            $reportManager->calculateQuarterWiseExpenseTotal($data);
        }

        if (isset($_GET['print'])) {
            $mpdf = MpdfFactory::create();
            $html = $this->renderView('@Budget/Report/QuarterWise/Income/quater-wise-income-pdf.html.twig', $data);
            $mpdf->WriteHTML($html);
            $mpdf->Output("Quater Wise Income Report - {$data['date']}.pdf", 'I');
        }
        if ($request->isMethod('POST')) {
            $new = array_merge($data['reportData'], $request->request->all());
            $policyManager->savePolicyJson('report_data.quarter_wise_budget_income.' . $data['year'], $new);

            $this->addFlash('success', 'Data Saved Successfully');

            return $this->redirectToRoute(
                'pre_budget_report_quarterly_income',
                ['year' => $data['year'], 'type' => $data['type']]
            );
        }

        return $this->render('@Budget/Report/QuarterWise/Income/quater-wise-income.html.twig', $data);
    }

    /**
     * @Route("/pre-budget-quarter-wise-income", name="pre_budget_report_quarterly_income")
     */
    public function preQuarterWiseIncomeAction(Request $request)
    {
        $data['year'] = $request->query->get('year');
        $data['type'] = $request->query->get('type', 'child-head');
        $policyManager = $this->get(PolicyManager::class);

        $data['reportData'] = $policyManager->getPolicyJson('report_data.quarter_wise_budget_income.' . $data['year']);
        if ($data['year']) {
            $reportManager = $this->get(ReportManager::class);
            $data = $data + $reportManager->getQuarterWiseIncome($data['year'], $data['type']);
            $reportManager->calculateQuarterWiseExpenseTotal($data);
        }

        if (isset($_GET['print'])) {
            $mpdf = MpdfFactory::create();
            $html = $this->renderView('@Budget/Report/QuarterWise/Income/pre-quater-wise-income-pdf.html.twig', $data);
            $mpdf->WriteHTML($html);
            $mpdf->Output("Pre Quater Wise Income Report - {$data['date']}.pdf", 'I');
        }
        if ($request->isMethod('POST')) {
            $new = array_merge($data['reportData'], $request->request->all());
            $policyManager->savePolicyJson('report_data.quarter_wise_budget_income.' . $data['year'], $new);

            $this->addFlash('success', 'Data Saved Successfully');

            return $this->redirectToRoute(
                'pre_budget_report_quarterly_income',
                ['year' => $data['year'], 'type' => $data['type']]
            );
        }

        return $this->render('@Budget/Report/QuarterWise/Income/pre-quater-wise-income.html.twig', $data);
    }


    /**
     * @Route("/office-expense-of-month", name="budget_report_expense_of_month")
     */
    public function officeExpenseOfMonthAction(Request $request)
    {
        $data = ['basb' => 'ডিএএসবি, ঢাকা', 'letterNo' => 'পত্র নং', 'date' => date("Y/m/d")];
        $data['year'] = $request->query->get('year');
        $data['month'] = $request->query->get('month');
        $data['reportForm'] = $this->createForm(ExpenseOfMonthReportForm::class, $data);
        if ($data['year'] && $data['month']) {
            $reportManager = $this->get(ReportManager::class);
            $data['budgetAmount'] = $this->baseBudgetRepo()->getAmounts($data['year'], 'status', $this->getOffice());
            $data['expenses'] = $reportManager->getExpenseTillMonthData(
                $data['year'],
                $data['month'],
                $this->getOffice()
            );
            $data['budgetParentHead'] = $this->baseBudgetRepo()->getTotalAmountParentHeadWise(
                $data['year'],
                'status',
                $this->getOffice()
            );
            $data['budgetTotalAmount'] = $this->baseBudgetRepo()->getTotalAmountOfYear(
                $data['year'],
                'status',
                $this->getOffice()
            );
            $data['budgetHead'] = $this->budgetHeadRepo()->getParentBudgetHead();
            if ($request->isMethod('POST')) {
                $data['reportForm']->handleRequest($request);
                $data = $data['reportForm']->getData() + $data;
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Budget/Report/expense-of-month-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Expense Of Month - {$data['year']} - {$data['month']}.pdf", 'I');
            }
        }
        $data['reportForm'] = $data['reportForm']->createView();
        return $this->render('@Budget/Report/expense-of-month.html.twig', $data);
    }
    /**
     * @Route("/all-office-expense-of-month", name="budget_report_all_office_expense_of_month")
     */
    public function expenseOfMonthAction(Request $request)
    {
        $data = ['basb' => 'ডিএএসবি, ঢাকা', 'letterNo' => 'পত্র নং', 'date' => date("Y/m/d")];
        $data['year'] = $request->query->get('year');
        $data['month'] = $request->query->get('month');
        $data['reportForm'] = $this->createForm(ExpenseOfMonthReportForm::class, $data);
        if ($data['year'] && $data['month']) {
            $reportManager = $this->get(ReportManager::class);
            $data['budgetAmount'] = $this->budgetSummaryDetailRepo()->getAmounts($data['year'], 'budgetStatus');
            $data['expenses'] = $reportManager->getAllExpenseTillMonthData(
                $data['year'],
                $data['month'],
                null
            );
            $data['budgetParentHead'] = $this->budgetSummaryDetailRepo()->getTotalAmountParentHeadWise(
                $data['year'],
                'budgetStatus'
            );
            $data['budgetTotalAmount'] = $this->budgetSummaryDetailRepo()->getTotalAmountOfYear(
                $data['year'],
                'budgetStatus'
            );
            $data['budgetHead'] = $this->budgetHeadRepo()->getParentBudgetHead();
            if ($request->isMethod('POST')) {
                $data['reportForm']->handleRequest($request);
                $data = $data['reportForm']->getData() + $data;
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Budget/Report/expense-of-un-implement-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Expense Of Month - {$data['year']} - {$data['month']}.pdf", 'I');
            }
        }
        $data['reportForm'] = $data['reportForm']->createView();
        return $this->render('@Budget/Report/expense-of-un-implement.html.twig', $data);
    }

    /**
     * @Route("/pre-budget-income-form-1", name="pre_budget_report_budget_income_form_1")
     */
    public function preBudgetIncomeForm1Action(Request $request)
    {
        $data = ['section' => 'প্রতিরক্ষা মন্ত্রনালয়', 'institute' => 'আন্তঃ বাহিনী ডিপার্টমেন্টস', 'unit' => 'বাংলাদেশ সশস্ত্র বাহিনী বোর্ড'];
        $data['year'] = $request->query->get('year');
        $data['reportForm'] = $this->createForm(BudgetRequestReportForm::class, $data);
        if ($data['year']) {
            $reportManager = $this->get(ReportManager::class);
            $data['budgetHead'] = $this->budgetIncomeHeadRepo()->getParentBudgetHead();
            $data = $data + $reportManager->getPreBudgetIncomeData($data['year']);
            if ($request->isMethod('POST')) {
                $data['reportForm']->handleRequest($request);
                $data = $data['reportForm']->getData() + $data;
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Budget/Report/pre-budget-income-form1-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Pre Budget Income Form 1 - {$data['year']}.pdf", 'I');
            }
        }
        $data['reportForm'] = $data['reportForm']->createView();
        return $this->render('@Budget/Report/pre-budget-income-form1.html.twig', $data);
    }

    /**
     * @Route("/pre-budget-expense-form-3", name="pre_budget_report_budget_expense_form_3")
     */
    public function preBudgetExpenseForm3Action(Request $request)
    {
        $data['year'] = $request->query->get('year');
        if ($data['year']) {
            $reportManager = $this->get(ReportManager::class);
            $data['budgetHead'] = $this->budgetHeadRepo()->getParentBudgetHead();
            $data = $data + $reportManager->getPreBudgetExpenseData($data['year']);
            if (isset($_GET['budget_report_form_3_report'])) {
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Budget/Report/pre-budget-expense-form3-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Budget Request Form 3 - {$data['year']}.pdf", 'I');
            }
        }
        return $this->render('@Budget/Report/pre-budget-expense-form3.html.twig', $data);
    }

    /**
     * @Route("/budget-request-form-1", name="budget_report_budget_request_form_1")
     */
    public function budgetRequestForm1Action(Request $request)
    {
        $data = ['section' => 'প্রতিরক্ষা মন্ত্রনালয়', 'institute' => 'আন্তঃ বাহিনী ডিপার্টমেন্টস', 'unit' => 'বাংলাদেশ সশস্ত্র বাহিনী বোর্ড'];
        $data['year'] = $request->query->get('year');
        $data['reportForm'] = $this->createForm(BudgetRequestReportForm::class, $data);
        if ($data['year']) {
            $reportManager = $this->get(ReportManager::class);
            $data['budgetHead'] = $this->budgetIncomeHeadRepo()->getParentBudgetHead();
            $data = $data + $reportManager->getBudgetRequestIncomeData($data['year']);
            if ($request->isMethod('POST')) {
                $data['reportForm']->handleRequest($request);
                $data = $data['reportForm']->getData() + $data;
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Budget/Report/budget-request-form1-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Budget Request Form 1 - {$data['year']}.pdf", 'I');
            }
        }
        $data['reportForm'] = $data['reportForm']->createView();
        return $this->render('@Budget/Report/budget-request-form-1.html.twig', $data);
    }

    /**
     * @Route("/budget-request", name="budget_report_budget_request")
     */
    public function budgetRequestAction(Request $request)
    {
        $data = ['section' => 'প্রতিরক্ষা মন্ত্রনালয়', 'institute' => '১৯৩৫ আন্তঃ বাহিনী ডিপার্টমেন্টস', 'unit' => '০১২০ সশস্ত্র বাহিনী বোর্ড'];
        $data['year'] = $request->query->get('year');
        $data['reportForm'] = $this->createForm(BudgetRequestReportForm::class, $data);
        if ($data['year']) {
            $reportManager = $this->get(ReportManager::class);
            $data['budgetHead'] = $this->budgetHeadRepo()->getParentBudgetHead();
            $data = $data + $reportManager->getBudgetRequestExpenseData($data['year']);
            if ($request->isMethod('POST')) {
                $data['reportForm']->handleRequest($request);
                $data = $data['reportForm']->getData() + $data;
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Budget/Report/budget-request-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Budget Request Form 2 - {$data['year']}.pdf", 'I');
            }
        }
        $data['reportForm'] = $data['reportForm']->createView();
        return $this->render('@Budget/Report/budget-request.html.twig', $data);
    }

    /**
     * @Route("/budget-request-form-3", name="budget_report_budget_request_form_3")
     */
    public function budgetRequestForm3Action(Request $request)
    {
        $data['year'] = $request->query->get('year');
        if ($data['year']) {
            $reportManager = $this->get(ReportManager::class);
            $data['budgetHead'] = $this->budgetHeadRepo()->getParentBudgetHead();
            $data = $data + $reportManager->getBudgetRequestExpenseData($data['year']);
            if (isset($_GET['budget_report_form_3_report'])) {
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Budget/Report/budget-request-form-3-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Budget Request Form 3 - {$data['year']}.pdf", 'I');
            }
        }
        return $this->render('@Budget/Report/budget-request-form-3.html.twig', $data);
    }

    /**
     * @Route("/budget-surrender", name="budget_report_surrender")
     */
    public function budgetSurrenderAction(Request $request)
    {
        $data = ['potro_no' => '২৩.০৭.০০০.০০৪.১৪.০০১.১৬', 'mod_potro_no' => '২৩.০০.০০০০.১০১.২০.০২৯.১৫-৩৩১', 'mod_potro_date' => '২১ এপ্রিল ২০১৬', 'prapok_designation' => 'সিনিয়র সচিব'];
        $data['year'] = $request->query->get('year');
        $data['reportForm'] = $this->createForm(BudgetSurrenderReportForm::class, $data);
        if ($data['year']) {
            $reportManager = $this->get(ReportManager::class);
            $data['budgetAmount'] = $this->budgetSummaryDetailRepo()->getAmounts($data['year'], 'budgetStatus');
            $data['expenses'] = $reportManager->getSurrenderExpenseData($data['year']);
            $data['budgetParentHead'] = $this->budgetSummaryDetailRepo()->getTotalAmountParentHeadWise(
                $data['year'],
                'budgetStatus'
            );
            $data['budgetTotalAmount'] = $this->budgetSummaryDetailRepo()->getTotalAmountOfYear(
                $data['year'],
                'budgetStatus'
            );
            $data['budgetHead'] = $this->budgetHeadRepo()->getParentBudgetHead();
            if ($request->isMethod('POST')) {
                $data['reportForm']->handleRequest($request);
                $data = $data['reportForm']->getData() + $data;
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Budget/Report/budget-surrender-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Budget Surrender - {$data['year']}.pdf", 'I');
            }
        }
        $data['reportForm'] = $data['reportForm']->createView();
        return $this->render('@Budget/Report/budget-surrender.html.twig', $data);
    }
    /**
     * @Route("/budget-amendment-form1", name="budget_report_budget_amendment_form1")
     */
    public function budgetAmendmentForm1Action(Request $request)
    {
        $data = ['section' => 'প্রতিরক্ষা মন্ত্রনালয়', 'institute' => 'আন্তঃ বাহিনী ডিপার্টমেন্টস', 'unit' => 'বাংলাদেশ সশস্ত্র বাহিনী বোর্ড'];
        $data['year'] = $request->query->get('year');
        $data['reportForm'] = $this->createForm(BudgetRequestReportForm::class, $data);
        if ($data['year']) {
            $reportManager = $this->get(ReportManager::class);
            $data['budgetHead'] = $this->budgetIncomeHeadRepo()->getParentBudgetHead();
            $data = $data + $reportManager->getBudgetIncomeAmendmentData($data['year']);
            if ($request->isMethod('POST')) {
                $data['reportForm']->handleRequest($request);
                $data = $data['reportForm']->getData() + $data;
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Budget/Report/budget-amendment-form1-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Budget Amendment Form 1 - {$data['year']}.pdf", 'I');
            }
        }
        $data['reportForm'] = $data['reportForm']->createView();
        return $this->render('@Budget/Report/budget-amendment-form1.html.twig', $data);
    }
    /**
     * @Route("/budget-amendment-form2", name="budget_report_budget_amendment_form2")
     */
    public function budgetAmendmentForm2Action(Request $request)
    {
        $data = ['section' => 'প্রতিরক্ষা মন্ত্রনালয়', 'institute' => '১৯৩৫ আন্তঃ বাহিনী ডিপার্টমেন্টস', 'unit' => '০১২০ সশস্ত্র বাহিনী বোর্ড'];
        $data['year'] = $request->query->get('year');
        $data['reportForm'] = $this->createForm(BudgetRequestReportForm::class, $data);
        if ($data['year']) {
            $reportManager = $this->get(ReportManager::class);
            $data['budgetHead'] = $this->budgetHeadRepo()->getParentBudgetHead();
            $data = $data + $reportManager->getBudgetAmendmentData($data['year']);
            if ($request->isMethod('POST')) {
                $data['reportForm']->handleRequest($request);
                $data = $data['reportForm']->getData() + $data;
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Budget/Report/budget-amendment-form2-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Budget Amendment Form 2 - {$data['year']}.pdf", 'I');
            }
        }
        $data['reportForm'] = $data['reportForm']->createView();
        return $this->render('@Budget/Report/budget-amendment-form2.html.twig', $data);
    }
    /**
     * @Route("/budget-amendment-form3", name="budget_report_budget_amendment_form3")
     */
    public function budgetAmendmentForm3Action(Request $request)
    {
        $data = ['section' => 'প্রতিরক্ষা মন্ত্রনালয়', 'institute' => '১৯৩৫ আন্তঃ বাহিনী ডিপার্টমেন্টস', 'unit' => '০১২০ সশস্ত্র বাহিনী বোর্ড'];
        $data['year'] = $request->query->get('year');
        $data['reportForm'] = $this->createForm(BudgetRequestReportForm::class, $data);
        if ($data['year']) {
            $reportManager = $this->get(ReportManager::class);
            $data['budgetHead'] = $this->budgetHeadRepo()->getParentBudgetHead();
            $data = $data + $reportManager->getBudgetAmendmentData($data['year']);
            if ($request->isMethod('POST')) {
                $data['reportForm']->handleRequest($request);
                $data = $data['reportForm']->getData() + $data;
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Budget/Report/budget-amendment-form3-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Budget Amendment Form 3 - {$data['year']}.pdf", 'I');
            }
        }
        $data['reportForm'] = $data['reportForm']->createView();
        return $this->render('@Budget/Report/budget-amendment-form3.html.twig', $data);
    }
    /**
     * @Route("/budget-all-office-expense", name="budget_report_budget_all_office_expense")
     */
    public function officeExpenseAction(Request $request)
    {
        $data['date'] = $request->query->get('date');
        $data['year'] = null;
        if ($data['date']) {
            $reportManager = $this->get(ReportManager::class);
            $data['year'] = DateUtil::getCurrentFinancialYear();
            $data = $data + $reportManager->getOfficeExpensesData($data);
            if (isset($_GET['print'])) {
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Budget/Report/all-office-expense-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("All Office Expense - {$data['date']}.pdf", 'I');
            }
        }
        return $this->render('@Budget/Report/all-office-expense.html.twig', $data);
    }
    /**
     * @Route("/monthly-expenses", name="budget_report_monthly_expenses")
     */
    public function monthlyExpensesAction(Request $request)
    {
        $data['year'] = $request->query->get('year');
        if ($data['year']) {
            $reportManager = $this->get(ReportManager::class);
            $data = $data + $reportManager->getMonthlyExpensesData($data);
        }
        return $this->render('@Budget/Report/monthly-expense.html.twig', $data);
    }
    /**
     * @Route("/monthly-expenses.pdf", name="budget_report_monthly_expenses_output")
     * @Method({"GET"})
     */
    public function monthlyExpensesOutputAction(Request $request)
    {
        $reportManager = $this->get(ReportManager::class);
        $data['year'] = $request->query->get('year');
        $data['budgetHead'] = $this->budgetHeadRepo()->getParentBudgetHead();
        $data = $data + $reportManager->getMonthlyExpensesData($data);
        $mpdf = MpdfFactory::create();
        $html = $this->renderView('@Budget/Report/monthly-expense-pdf.html.twig', $data);
        $mpdf->WriteHTML($html);
        $mpdf->Output("Monthly Expense - {$data['year']}.pdf", 'I');
        return $this->render('@Budget/Report/monthly-expense.html.twig', $data);
    }

}