<?php

namespace WelfareBundle\Controller;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use WelfareBundle\Manager\BaseWelfareManager;

/**
 * @Route("/welfare")
 */
class WelfareController extends BaseController
{
    /**
     * @Security("is_granted('ROLE_DASB_CLERK')")
     * @Route("/ex-serviceman/search", name="welfare_ex_serviceman_search")
     */
    public function exServicemanSearchAction(Request $request)
    {
        $serviceId = $request->query->get('service-id');

        if (!$serviceId) {
            return $this->renderSearchView(['errorMessage' => '']);
        }

        $manager = $this->get(BaseWelfareManager::class);
        $exServiceMan = $manager->getServiceManByServiceId($serviceId);
        if (empty($exServiceMan)) {
            return $this->renderSearchView(['isEligible' => false, 'errorMessage' => 'Soldier ID not found']);
        }

        if ($exServiceMan->getOffice() != $this->getOffice()) {
            return $this->renderSearchView(['isEligible' => false, 'errorMessage' => sprintf('%s does not belong to %s DASB', $exServiceMan->getName(), $this->getOffice())]);
        }

        $data['personnel'] = $exServiceMan;
        $data['welfareFunds'] = $this->welfareFunds($serviceId);
        return $this->render('WelfareBundle:Welfare:ex_serviceman_search.html.twig', $data);
    }

    private function welfareFunds($serviceId) {

        $str = '<select class="form-control" name="fundType" id="fundType">';
        $funds = $this->getDoctrine()->getRepository('PersonnelBundle:WelfareFund')->findAll();
        foreach ($funds as $fund) {
            switch ($fund) {
                case 'Bangladesh Serviceman Charitable Relief Fund (BSCR)':
                   $str .= '<option value="'.$this->generateUrl('welfare_bscr_create').'?service-id='.$serviceId.'">'.$fund.'</option>';
                   break;
                case 'Royal Commonwealth Ex-services League (RCEL)':
                   $str .= '<option value="'.$this->generateUrl('welfare_rcel_create').'?service-id='.$serviceId.'">'.$fund.'</option>';
                   break;
                default:
                   $str .= '<option value="">'.$fund.'</option>';
            }
        }
        $str .= '</select>';

        return $str;
    }

    private function renderSearchView($data)
    {
        return $this->render('WelfareBundle:Welfare:ex_serviceman_search.html.twig', $data);
    }
}
