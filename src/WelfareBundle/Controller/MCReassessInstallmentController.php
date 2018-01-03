<?php

namespace WelfareBundle\Controller;

use AppBundle\Controller\BaseController;
use BoardMeetingBundle\Entity\BoardMeeting;
use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use WelfareBundle\Datatables\MCReassessInstallmentDatatable;
use WelfareBundle\Entity\MCInstallment;
use WelfareBundle\Entity\MCReassessInstallment;
use WelfareBundle\Entity\MicroCreditApplication;
use WelfareBundle\Form\MCReassessInstallmentForm;

class MCReassessInstallmentController extends BaseController
{
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/micro-credit/reassess-installment/", name="welfare_mc_reassess_installment_index")
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(MCReassessInstallmentDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice() && strtolower($this->getOffice()->getOfficeType()->getName()) == 'dasb') {
                $qb->andWhere("mcreassessinstallment.office = :office")->setParameter('office', $this->getOffice());
            }
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('WelfareBundle:MCReassessInstallment:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * @Security("is_granted('ROLE_DASB_CLERK')")
     * @Route("/welfare/micro-credit/reassess-installment/create/{id}", name="welfare_mc_reassess_installment_create")
     * @param Request $request
     * @param MicroCreditApplication $application
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request, MicroCreditApplication $application)
    {
        if ($application->getOffice() != $this->getOffice()) {
            throw $this->createAccessDeniedException('Illegal Office');
        }

        $isDefaulter = $this->getDoctrine()->getRepository('WelfareBundle:MCDefaulter')->findOneBy([
            'application' => $application]);

        if (!$isDefaulter) {
            throw $this->createAccessDeniedException('Not a defaulter');
        }

        $reassess = new MCReassessInstallment();
        $reassess->setOffice($application->getOffice());
        $reassess->setApplication($application);
        $reassess->setStatus('draft');

        $form = $this->createForm(MCReassessInstallmentForm::class, $reassess);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($reassess);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('welfare_mc_reassess_installment_index');
            }
        }

        return $this->_renderView($form, $reassess->getApplication());
    }

    /**
     * @Security("is_granted('edit:mc_reassess_installment', reassessInstallment)")
     * @Route("/welfare/micro-credit/reassess-installment/{id}/edit", name="welfare_mc_reassess_installment_edit")
     * @internal param MCReassessInstallment $reassess
     * @param Request $request
     * @param MCReassessInstallment $reassessInstallment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, MCReassessInstallment $reassessInstallment)
    {
        if ($reassessInstallment->getOffice() != $this->getOffice()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(MCReassessInstallmentForm::class, $reassessInstallment);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($reassessInstallment);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('welfare_mc_reassess_installment_index');
            }
        }

        return $this->_renderView($form, $reassessInstallment->getApplication());
    }

    private function _renderView($form, $application) {
        return $this->render('WelfareBundle:MCReassessInstallment:create.html.twig', array(
            'form' => $form->createView(),
            'application' => $application,
            'payments' => $this->getDoctrine()->getRepository(
                'WelfareBundle:MicroCreditPayment')->paymentHistory($application)
        ));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfaremicro-credit/reassess-installment/view/{id}", name="welfare_mc_reassess_installment_view")
     * @param MCReassessInstallment $reassessInstallment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(MCReassessInstallment $reassessInstallment)
    {
        return $this->render('WelfareBundle:MCReassessInstallment:view.html.twig', [
            'application' => $reassessInstallment->getApplication(),
            'reassessInstallment' => $reassessInstallment,
            'payments' => $this->getDoctrine()->getRepository(
                'WelfareBundle:MicroCreditPayment')->paymentHistory($reassessInstallment->getApplication())
        ]);
    }

    /**
     * @Security("is_granted('ROLE_DIRECTOR')")
     * @Route("/welfaremicro-credit/reassess-installment/set/{id}", name="welfare_mc_reassess_installment_set")
     * @param MCReassessInstallment $reassessInstallment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function setReassessedInstallmentAction(Request $request, MCReassessInstallment $reassessInstallment)
    {
        $installmentAmount = $request->request->get('installmentAmount', 0);
        $redirectUrl = $request->request->get('redirectUrl', '');

        $reassessInstallment->setInstallmentAmount($installmentAmount);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($redirectUrl);
    }

}
