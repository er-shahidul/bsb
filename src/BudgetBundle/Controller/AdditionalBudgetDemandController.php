<?php

namespace BudgetBundle\Controller;

use AppBundle\Entity\FinancialYear;
use AppBundle\Utility\DateUtil;
use BudgetBundle\Datatables\FundDatatable;
use BudgetBundle\Entity\Budget;
use BudgetBundle\Entity\BudgetDetail;
use BudgetBundle\Entity\BudgetHead;
use BudgetBundle\Entity\FundRequest;
use BudgetBundle\Event\BudgetEvent;
use BudgetBundle\Form\Type\BudgetHeadType;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/additional-budget-demand")
 */
class AdditionalBudgetDemandController extends BudgetBaseController
{
    /**
     * @Route("/list", name="budget_fund_request_list", options={"expose"=true})
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(FundDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("fundrequest.office = :office");
            $qb->setParameter('office', $this->getUser()->getOffice());
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        $financialYear = new FinancialYear(DateUtil::getCurrentFinancialYear());
        $budget = $this->budgetRepo()->getBudgetByYear($financialYear, $this->getOffice());

        return $this->render(
            '@Budget/FundRequest/index.html.twig',
            [
                'datatable'       => $datatable,
                'budget'          => $budget,
                'financialYear'   => $financialYear,
            ]
        );
    }

    /**
     * @Route("/create", name="budget_fund_request_create")
     * @Security("has_role('ROLE_DASB_CLERK') or has_role('ROLE_BUDGET_CLERK')")
     */
    public function createAction(Request $request)
    {
        $budgetHeadForm = $this->createFormBuilder()->add('budgetHeads', BudgetHeadType::class)->getForm();
        $budget = new Budget();
        $currentFinancialYear = $this->financialYearRepo()->getActiveFinancialYear();

        if ($this->isCsrfTokenValid('budget_fund_request', $request->request->get('_csrf_token'))) {

            $budget = $this->fundRequestRepo()->createFundRequest($request->request->get('data'), $currentFinancialYear, $this->getOffice());

            $this->dispatch('budget.created', new BudgetEvent($budget, []));

            $this->addFlash('success', 'Additional budget has been demanded for financial year '.$budget->getFinancialYear()->getLabel().' successfully');

            return $this->redirectToRoute('budget_fund_request_list');
        }

        return $this->render('@Budget/FundRequest/create.html.twig', [
            'budgetHeadForm' => $budgetHeadForm->createView(),
            'budget' => $budget,
            'budgetYear' => $currentFinancialYear->getId(),
        ]);
    }

    /**
     * @Route("/update/{id}", name="budget_fund_request_update", options={"expose"=true})
     * @Security("is_granted('SAME_OFFICE', budget) and is_granted('edit:fund_request:draft', budget)")
     */
    public function updateAction(Request $request, FundRequest $budget)
    {
        $budgetHeadForm = $this->createFormBuilder()->add('budgetHeads', BudgetHeadType::class)->getForm();
        $currentFinancialYear = $budget->getFinancialYear()->getId();
        $this->getBudgetManager()->setBudgetStats($budget);

        if ($this->isCsrfTokenValid('budget_fund_request', $request->request->get('_csrf_token'))) {

            $this->fundRequestRepo()->updateFundRequest($budget, $request->request->get('data'));

            $this->dispatch('budget.updated', new BudgetEvent($budget, []));

            $this->addFlash('success', 'Additional budget demand has been updated for financial year '.$budget->getFinancialYear()->getLabel().' successfully');

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render('@Budget/FundRequest/create.html.twig', [
            'budgetHeadForm' => $budgetHeadForm->createView(),
            'budget' => $budget,
            'budgetYear' => $currentFinancialYear,
        ]);
    }

    /**
     * @Route("/view/{id}", name="budget_fund_request_view", options={"expose"=true})
     * @Security("(has_role('ROLE_BUDGET') or is_granted('DASB_USER')) and is_granted('SAME_OFFICE', budget)")
     */
    public function viewAction(FundRequest $budget)
    {
        $this->getBudgetManager()->setBudgetStats($budget);

        return $this->render(
            '@Budget/FundRequest/view.html.twig',
            [
                'budget' => $budget,
                'budgetYear' => $budget->getFinancialYear()->getId(),
            ]
        );
    }

    /**
     * @Route("/stats/{id}", name="budget_head_stats", options={"expose"=true})
     * @Security("has_role('ROLE_DASB_CLERK') or has_role('ROLE_BUDGET_CLERK')")
     */
    public function summaryAction(BudgetHead $budgetHead)
    {
        $currentFinancialYear = DateUtil::getCurrentFinancialYear();
        $office = $this->getOffice();

        return new JsonResponse(
            $this->getBudgetManager()->getBudgetHeadStats($budgetHead, $currentFinancialYear, $office)
        );
    }

    /**
     * @Route("/allocation/{id}", name="budget_fund_request_allocation", options={"expose"=true})
     * @Security("is_granted('ROLE_BUDGET_CLERK') and is_granted('edit:fund_request:approval_wait_for_clerk', budget)")
     */
    public function allocationAction(Request $request, FundRequest $budget)
    {
        $budgetHeadForm = $this->createFormBuilder()->add('budgetHeads', BudgetHeadType::class)->getForm();
        $currentFinancialYear = $budget->getFinancialYear()->getId();

        $this->getBudgetManager()->setBudgetStats($budget);

        if ($this->isCsrfTokenValid('budget_fund_allocation', $request->request->get('_csrf_token'))) {

            $data = $request->request->get('data');
            if (!$this->validateAllocationUpdate($budget, $data)) {
                $this->addFlash('error', 'Invalid Operation');

                return $this->redirectToRoute('budget_fund_request_list');
            }

            $this->fundRequestRepo()->updateFundRequestAllocation($data);

            $this->dispatch('budget.updated', new BudgetEvent($budget, []));

            $this->addFlash('success', 'Fund request updated successfully');

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render('@Budget/FundRequest/allocation.html.twig', [
            'budgetHeadForm' => $budgetHeadForm->createView(),
            'budget' => $budget,
            'budgetYear' => $currentFinancialYear,
        ]);
    }

    private function validateAllocationUpdate(FundRequest $budget, $data)
    {
        /** @var BudgetDetail $budgetDetail */
        foreach ($budget->getBudgetDetails() as $budgetDetail) {
            if (!array_key_exists($budgetDetail->getId(), $data)) {
                return false;
            }

            if ((float)$data[$budgetDetail->getId()]['amount'] > $budgetDetail->stats['headRemaining']) {
                return false;
            }
        }

        return true;
    }
}