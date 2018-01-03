<?php

namespace PersonnelBundle\Controller;

use ReflectionClass;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DashboardController extends BaseController
{
    /**
     * @Route("/", name="personnel_dashboard")
     */
    public function indexAction()
    {
        return $this->render('PersonnelBundle:Dashboard:index.html.twig');
    }

    /**
     * @Route("/find_all_serviceman_information", name="find_all_serviceman_information", options={"expose":true})
     * @param Request $request
     * @return JsonResponse
     */
    public function findAllServicemanInformationAction(Request $request)
    {
        $requestArray = $request->request->get('requestArray');
        $requestArray = explode(',', $requestArray);

        $identityNumber = $requestArray[0];
        $servingType = $requestArray[1];

        $servingPerson =  $this->getEntityClass($identityNumber, $servingType);
        $ref = new ReflectionClass($servingPerson);

        $servingPersonArr = [];
        if ($servingPerson) {
                $servingPersonArr['servingPersonName'] = $servingPerson->getName();
                if($ref->getShortName() == "ServingCivilian") $servingPersonArr['servingPersonRank'] = $servingPerson->getDesignation();
                else $servingPersonArr['servingPersonRank'] = $servingPerson->getMilitaryProfile()->getRank()->getName();
        }

        return new JsonResponse([
            'servingPersonArr' => $servingPersonArr
        ]);
    }

    public function getEntityClass($identityNumber, $servingType)
    {
        switch ($servingType) {
            case 1:
                $servingPerson = $this->getDoctrine()->getRepository('PersonnelBundle:ServingCivilian')->find($identityNumber);
                break;
            case 2:
                $servingPerson = $this->getDoctrine()->getRepository('PersonnelBundle:ServingMilitary')->find($identityNumber);
                break;
            default:
                $servingPerson = $this->getDoctrine()->getRepository('PersonnelBundle:ServingPersonnel')->find($identityNumber);
                break;
        }
        return $servingPerson;
    }
}
