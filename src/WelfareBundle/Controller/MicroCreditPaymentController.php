<?php

namespace WelfareBundle\Controller;

use AppBundle\Controller\BaseController;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WelfareBundle\Datatables\MCDefaulterPaymentDatatable;
use WelfareBundle\Datatables\MCDefaulterDatatable;
use WelfareBundle\Datatables\MicroCreditPaymentDatatable;
use WelfareBundle\Entity\MCDefaulter;
use WelfareBundle\Entity\MCDefaulterRegister;
use WelfareBundle\Entity\MicroCreditApplication;
use WelfareBundle\Entity\MCInstallment;
use WelfareBundle\Entity\MicroCreditPayment;
use WelfareBundle\Form\PaymentReceiveForm;
use WelfareBundle\Manager\MCPaymentManager;

class MicroCreditPaymentController extends BaseController
{
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/micro-credit/payments", name="welfare_micro_credit_payment_index")
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(MicroCreditPaymentDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice() && strtolower($this->getOffice()->getOfficeType()->getName()) == 'dasb') {
                $qb->andWhere("microcreditpayment.office = :office")->setParameter('office', $this->getOffice());
            }
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('WelfareBundle:MicroCreditPayment:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * @Security("is_granted('ROLE_DASB_CLERK')")
     * @Route("/welfare/micro-credit/payment/new-receive", name="welfare_micro_credit_payment_new_receive")
     */
    public function newReceiveAction(Request $request)
    {
        $errorMessage = '';
        $serviceId = $request->query->get('service-id');

        if ($serviceId) {
            /** @var MCInstallment $installment */
            $installment = $this->getDoctrine()->getRepository('WelfareBundle:MCInstallment')->hasInstallment($serviceId);
            if ($installment) {
                return $this->redirectToRoute('welfare_micro_credit_payment_receive', ['id' => $installment->getApplication()->getId()]);
            }
            $errorMessage = 'Loan applicant not found';
        }

        return $this->render('WelfareBundle:MicroCreditPayment:receive.html.twig', array(
            'errorMessage' => $errorMessage,
            'application' => null
        ));
    }

    /**
     * @Security("is_granted('ROLE_DASB_CLERK')")
     * @Route("/welfare/micro-credit/payment/receive/{id}", name="welfare_micro_credit_payment_receive")
     * @param Request $request
     * @param MicroCreditApplication $application
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createPaymentAction(Request $request, MicroCreditApplication $application)
    {
        if ($application->getOffice() != $this->getOffice()) {
            throw $this->createAccessDeniedException('Illegal Office');
        }

        $payment = new MicroCreditPayment();
        $payment->setOffice($application->getOffice());
        $payment->setApplication($application);
        $payment->setStatus('draft');

        $form = $this->createForm(PaymentReceiveForm::class, $payment);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($payment);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('welfare_micro_credit_payment_index');
            }
        }

        return $this->_renderView($form, $payment->getApplication());
    }

    /**
     * @Security("is_granted('edit:welfare_micro_credit_payment', payment)")
     * @Route("/welfare/micro-credit/payment/receive/{id}/edit", name="welfare_micro_credit_payment_receive_edit")
     * @param Request $request
     * @param MicroCreditPayment $payment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, MicroCreditPayment $payment)
    {
        if ($payment->getOffice() != $this->getOffice()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(PaymentReceiveForm::class, $payment);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($payment);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('welfare_micro_credit_payment_index');
            }
        }

        return $this->_renderView($form, $payment->getApplication());
    }

    private function _renderView($form, $application) {
        return $this->render('WelfareBundle:MicroCreditPayment:receive.html.twig', array(
            'form' => $form->createView(),
            'application' => $application,
            'payments' => $this->getDoctrine()->getRepository(
                'WelfareBundle:MicroCreditPayment')->paymentHistory($application)
        ));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/micro-credit/payment/view/{id}", name="welfare_micro_credit_payment_view")
     * @param MicroCreditPayment $payment
     * @return Response
     */
    public function viewAction(MicroCreditPayment $payment)
    {
        return $this->render('WelfareBundle:MicroCreditPayment:view.html.twig', [
            'application' => $payment->getApplication(),
            'payment' => $payment,
            'payments' => $this->getDoctrine()->getRepository(
                'WelfareBundle:MicroCreditPayment')->paymentHistory($payment->getApplication())
        ]);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/micro-credit/applicant-payments/{id}", name="welfare_micro_credit_payment_history")
     * @param MicroCreditApplication $application
     * @return Response
     * @internal param MicroCreditPayment $payment
     */
    public function paymentHistoryAction(MicroCreditApplication $application)
    {
        return $this->render('WelfareBundle:MicroCreditPayment:view.html.twig', [
            'application' => $application,
            'payment' => null,
            'payments' => $this->getDoctrine()->getRepository(
                'WelfareBundle:MicroCreditPayment')->paymentHistory($application)
        ]);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/micro-credit/defaulters", name="welfare_micro_credit_defaulters")
     */
    public function defaultersAction(Request $request) {

        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(MCDefaulterDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice() && strtolower($this->getOffice()->getOfficeType()->getName()) == 'dasb') {
                $qb->join('mcdefaulter.application', 'm');
                $qb->andWhere("m.office = :office")->setParameter('office', $this->getOffice());
            }
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('WelfareBundle:MicroCreditPayment:defaulters.html.twig', array(
            'datatable' => $datatable,
        ));
    }
}
