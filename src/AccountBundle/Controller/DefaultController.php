<?php

namespace AccountBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends AccountBaseController
{
    /**
     * @Route("/dashboard", name="account_dashboard")
     */
    public function indexAction()
    {
        return $this->render('AccountBundle:Default:index.html.twig');
    }
}
