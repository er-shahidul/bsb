<?php

namespace MedicalBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends BaseMedicalController
{
    /**
     * @Route("/dashboard", name="medical_dashboard")
     */
    public function indexAction()
    {
        return $this->render('MedicalBundle:Default:index.html.twig');
    }
}
