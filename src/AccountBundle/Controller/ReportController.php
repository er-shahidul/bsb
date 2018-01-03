<?php

namespace AccountBundle\Controller;

use AccountBundle\Form\Report\VoucherReportForm;
use AccountBundle\Manager\ReportManager;
use Libs\Mpdf\MpdfFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * ReportController controller.
 *
 * @Route("report")
 */
class ReportController extends AccountBaseController
{
    /**
     * @Route("/bank_reconciliation", name="account_report_bank_reconciliation")
     * @Security("has_role('ROLE_ACCOUNT') or is_granted('DASB_USER')")
     */
    public function bankReconciliationAction(Request $request)
    {
        $data['office'] = $this->getOffice();
        $data['year'] = $request->query->get('year');
        $data['month'] = $request->query->get('month');
        $data['bankAccountId'] = $request->query->get('bankAccount');
        $data['bankAccounts'] = $this->bankAccountRepo()->getBankAccountByOffice($this->getOffice());

        if ($data['year']) {

            $reportManager = $this->get(ReportManager::class);
            $data['bankAccount'] = $this->bankAccountRepo()->find($data['bankAccountId']);

            $data = $data + $reportManager->getBankReconciliationData($data);

            if ($request->query->get('pdf') == 'true') {
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Account/Report/reconciliation/reconciliation-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Payment Reconciliation - {$data['year']}-{$data['month']}.pdf", 'I');
            }
        }

        return $this->render('@Account/Report/reconciliation/reconciliation.html.twig', $data);
    }

    /**
     * @Route("/receive-voucher", name="account_report_receive_voucher")
     * @Security("has_role('ROLE_ACCOUNT') or is_granted('DASB_USER')")
     */
    public function receiveVoucherAction(Request $request)
    {
        $data['office'] = $this->getOffice();
        $data['type'] = 'payer';

        $data['reportForm'] = $this->createForm(VoucherReportForm::class, $request->query->get('voucher_report_form'), ['office' => $this->getOffice(), 'type' => $data['type']]);
        $data['reportForm']->handleRequest($request);
        $data['formData'] = $data['reportForm']->getData();

        if (count($data['formData']) == 7) {
            $reportManager = $this->get(ReportManager::class);

            $data = $data + $reportManager->getVoucherReportData($data, 'receive');

            if ($request->isMethod('POST')) {
                $fundReceiveData = $request->request->get('voucher_report_form');
                $data['title'] = isset($fundReceiveData['title']) ? $fundReceiveData['title'] : null;

                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Account/Report/receive-voucher/fund-receive-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Receive Vouchers.pdf", 'I');
            }
        }

        $data['reportForm'] = $data['reportForm']->createView();

        return $this->render('@Account/Report/receive-voucher/fund-receive.html.twig', $data);
    }

    /**
     * @Route("/payment-voucher", name="account_report_payment_voucher")
     * @Security("has_role('ROLE_ACCOUNT') or is_granted('DASB_USER')")
     */
    public function paymentVoucherAction(Request $request)
    {
        $data['office'] = $this->getOffice();
        $data['type'] = 'payee';

        $data['reportForm'] = $this->createForm(VoucherReportForm::class, $request->query->get('voucher_report_form'), ['office' => $this->getOffice(), 'type' => $data['type']]);
        $data['reportForm']->handleRequest($request);
        $data['formData'] = $data['reportForm']->getData();

        if (count($data['formData']) == 7) {
            $reportManager = $this->get(ReportManager::class);

            $data = $data + $reportManager->getVoucherReportData($data, 'payment');

            if ($request->isMethod('POST')) {
                $fundReceiveData = $request->request->get('voucher_report_form');
                $data['title'] = isset($fundReceiveData['title']) ? $fundReceiveData['title'] : null;

                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Account/Report/payment-voucher/fund-receive-pdf.html.twig', $data);
                $mpdf->WriteHTML($html);
                $mpdf->Output("Receive Vouchers.pdf", 'I');
            }
        }

        $data['reportForm'] = $data['reportForm']->createView();

        return $this->render('@Account/Report/payment-voucher/fund-receive.html.twig', $data);
    }

    /**
     * @Route("/from-to-data/{fundType}/{type}", name="account_report_from_to_data", options={"expose"=true})
     * @Security("has_role('ROLE_ACCOUNT') or is_granted('DASB_USER')")
     */
    public function getFromToDataAction($fundType, $type)
    {
        $type = ucfirst($type);
        return new JsonResponse($this->getRepository("AccountBundle:{$type}")->getDropDownOption($fundType));
    }
}
