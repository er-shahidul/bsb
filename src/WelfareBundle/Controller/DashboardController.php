<?php

namespace WelfareBundle\Controller;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/welfare")
 */
class DashboardController extends BaseController
{
    /**
     * @Route("/dashboard", name="welfare_dashboard")
     */
    public function indexAction(Request $request)
    {
        $office = $this->isGranted('ROLE_SUPER_ADMIN') ? $this->getDoctrine()->getRepository('AppBundle:Office')->getHQOffice() : $this->getUser()->getOffice();

        return $this->render('@Welfare/Dashboard/index.html.twig');
    }

    /**
     * @Route("/dashboard-micro-credit", name="welfare_dashboard_micro_credit")
     */
    public function microCreditAction(Request $request)
    {
        $office = $this->isGranted('ROLE_SUPER_ADMIN') ? $this->getDoctrine()->getRepository('AppBundle:Office')->getHQOffice() : $this->getUser()->getOffice();

        return $this->render('@Welfare/Dashboard/micro_credit.html.twig');
    }
}
