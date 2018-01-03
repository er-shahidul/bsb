<?php

namespace PersonnelBundle\Controller;

use AppBundle\Controller\BaseController;

class PersonnelBaseController extends BaseController
{
    protected function exServicemanRepo()
    {
        return $this->getDoctrine()->getRepository('PersonnelBundle:ExServiceman');
    }

    protected function servingMilitaryRepo()
    {
        return $this->getDoctrine()->getRepository('PersonnelBundle:ServingMilitary');
    }

    protected function servingCivilianRepo()
    {
        return $this->getDoctrine()->getRepository('PersonnelBundle:ServingCivilian');
    }

    protected function genderRepo()
    {
        return $this->getDoctrine()->getRepository('PersonnelBundle:Gender');
    }
}