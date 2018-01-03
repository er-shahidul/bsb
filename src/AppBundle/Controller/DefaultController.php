<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage", options={"expose"=true})
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('@App/default/index.html.twig');
    }

    /**
     * @Route("/master-data", name="master_data_dashboard", options={"expose"=true})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function masterDataIndexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('@App/default/master-data.html.twig');
    }
}
