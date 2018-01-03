<?php

namespace AccountBundle\Controller;

use AccountBundle\Manager\ReportManager;
use Libs\Mpdf\MpdfFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * LedgerAccount controller.
 *
 * @Route("ledger")
 */
class LedgerController extends AccountBaseController
{
    /**
     * @Route("/payment", name="account_ledger_payment")
     * @Security("has_role('ROLE_ACCOUNT') or is_granted('DASB_USER')")
     */
    public function paymentAction(Request $request)
    {
        $data['office'] = $this->getOffice();
        $data['year'] = $request->query->get('year');
        $data['month'] = $request->query->get('month');
        $data['fundTypeId'] = $request->query->get('fundType');
        $data['fundTypes'] = $this->getFundTypes();

        if ($data['year']) {

            $reportManager = $this->get(ReportManager::class);
            $data['fundType'] = $this->fundTypeRepo()->find($data['fundTypeId']);
            $data['fundHeads'] = $this->fundHead()->fundHeadByFundType($data['fundType'], $this->getOffice()->getOfficeType());
            $data = $data + $reportManager->getPaymentData($data);

            if ($request->query->get('pdf') == 'true') {
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Account/Ledger/payment-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Payment Ledger - {$data['year']}-{$data['month']}.pdf", 'I');
            }
        }

        return $this->render('@Account/Ledger/payment.html.twig', $data);
    }

    /**
     * @Route("/receive", name="account_ledger_receive")
     * @Security("has_role('ROLE_ACCOUNT') or is_granted('DASB_USER')")
     */
    public function receiveAction(Request $request)
    {
        $data['office'] = $this->getOffice();
        $data['year'] = $request->query->get('year');
        $data['month'] = $request->query->get('month');
        $data['fundTypeId'] = $request->query->get('fundType');
        $data['fundTypes'] = $this->getFundTypes();

        if ($data['year']) {

            $reportManager = $this->get(ReportManager::class);
            $data['fundType'] = $this->fundTypeRepo()->find($data['fundTypeId']);
            $data['fundHeads'] = $this->fundHead()->fundHeadByFundType($data['fundType'], $this->getOffice()->getOfficeType());
            $data = $data + $reportManager->getReceiveData($data);

            if ($request->query->get('pdf') == 'true') {
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Account/Ledger/receive-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Receive Ledger - {$data['year']}-{$data['month']}.pdf", 'I');
            }
        }

        return $this->render('@Account/Ledger/receive.html.twig', $data);
    }
}
