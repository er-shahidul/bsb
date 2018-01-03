<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/portal")
 */
class PortalController extends UserBaseController
{
    /**
     * @Route("")
     */
    public function indexAction()
    {
        return $this->render('UserBundle:Default:index.html.twig');
    }

    /**
     * @Route("form1/step1")
     */
    public function form1Step1Action()
    {
        return $this->render('UserBundle:Default:index.html.twig');
    }

    /**
     * @Route("form1/step2")
     */
    public function form1Step2Action()
    {
        return $this->render('UserBundle:Default:index.html.twig');
    }
}
