<?php

namespace LeaveBundle\Controller;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DashboardController extends BaseController
{
    /**
     * @Route("/", name="leave_dashboard")
     */
    public function indexAction()
    {
        return $this->render('LeaveBundle:Dashboard:index.html.twig', array(
            'isBasb' => $this->getOffice()?$this->getOffice()->isBasb():true,
        ));
    }
}
