<?php

namespace BudgetBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/dashboard")
 */
class DashboardController extends BudgetBaseController
{
    /**
     * @Route("", name="budget_dashboard")
     */
    public function indexAction(Request $request)
    {
        $office = $this->isGranted('ROLE_SUPER_ADMIN') ? $this->getDoctrine()->getRepository('AppBundle:Office')->getHQOffice() : $this->getUser()->getOffice();
        $budget = $this->budgetRepo()->getCurrentYearBudget($office);

        return $this->render('@Budget/Dashboard/index.html.twig', [
            'budget' => $budget
        ]);
    }
}
