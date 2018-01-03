<?php

namespace FuneralBundle\Controller;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DashboardController extends BaseController
{
    /**
     * @Route("/", name="funeral_dashboard")
     */
    public function indexAction()
    {
        return $this->render('FuneralBundle:Dashboard:index.html.twig');
    }
}
