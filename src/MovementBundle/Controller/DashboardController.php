<?php

namespace MovementBundle\Controller;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DashboardController extends BaseController
{
    /**
     * @Route("/", name="movement_dashboard")
     */
    public function indexAction()
    {
        return $this->render('MovementBundle:Dashboard:index.html.twig');
    }
}
