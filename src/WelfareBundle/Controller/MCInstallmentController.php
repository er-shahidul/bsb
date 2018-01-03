<?php

namespace WelfareBundle\Controller;

use AppBundle\Controller\BaseController;
use BoardMeetingBundle\Entity\BoardMeeting;
use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use WelfareBundle\Entity\MicroCreditApplication;

class MCInstallmentController extends BaseController
{
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/micro-credit/installment/{id}", name="welfare_micro_credit_installment_index")
     */
    public function indexAction(Request $request, MicroCreditApplication $application)
    {
        $installments = $this->getDoctrine()->getRepository('WelfareBundle:MCInstallment')->findBy([
            'application' => $application]);

        return $this->render('@Welfare/MicorCreditInstallment/index.html.twig', array(
            'installments' => $installments,
            'application' => $application,
            'freeMonthsCount' => $this->get(PolicyManager::class)->getPolicyValue('welfare.micro_credit_payment_free_month_count')
        ));
    }
}
