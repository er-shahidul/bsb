<?php

namespace PersonnelBundle\Controller;

use PersonnelBundle\Entity\District;
use PersonnelBundle\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/rest/")
 * @Security("has_role('ROLE_ESTABLISHMENT_CLERK') or has_role('ROLE_WELFARE_CLERK')  or has_role('ROLE_DASB_CLERK') ")
 */
class RestController extends AbstractController
{

    /**
     * @Route("/{district}/upazila", name="personnel_upazila_lookup", options={"expose"=true})
     * @param District $district
     *
     * @return JsonResponse
     */
    public function indexAction(District $district)
    {
        $upazilas = [];
        foreach ($district->getUpazilas() as $upazila) {
            $upazilas[] = [
                'id'   => $upazila->getId(),
                'name' => $upazila->getName(),
            ];
        }

        return new JsonResponse($upazilas);
    }

    /**
     * @Route("/service-child-lookup/{service}", name="personnel_service_child_lookup", options={"expose"=true})
     * @param Service $service
     *
     * @return JsonResponse
     */
    public function serviceChildLookupAction(Service $service)
    {
        $corps = [];

        foreach ($service->getCorps() as $corp) {
            $corps[] = [
                'id'   => $corp->getId(),
                'name' => $corp->getName(),
            ];
        }

        $ranks = [];
        foreach ($service->getRanks() as $rank) {
            $ranks[] = [
                'id'   => $rank->getId(),
                'name' => $rank->getName(),
            ];
        }

        return new JsonResponse([
            'corps' => $corps,
            'ranks' => $ranks
        ]);
    }
}