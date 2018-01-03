<?php

namespace WelfareBundle\Controller;

use AppBundle\Controller\BaseController;
use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Doctrine\DBAL\Types\Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;
use WelfareBundle\Datatables\MicroCreditApplicationDatatable;
use WelfareBundle\Datatables\MicroCreditLoanRecipientDatatable;
use WelfareBundle\Entity\MicroCreditApplication;
use WelfareBundle\Form\MicroCreditApplicationForm;
use WelfareBundle\Manager\MicroCreditManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use WelfareBundle\Manager\WelfareManager;
use WelfareBundle\Validator\Constraints\ServiceIdExists;

class MicroCreditController extends BaseController
{
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/micro-credit", name="welfare_micro_credit_index")
     * @param Request $request
     * @return DatatableInterface|Response
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(MicroCreditApplicationDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice() && strtolower($this->getOffice()->getOfficeType()->getName()) == 'dasb') {
                $qb->andWhere("microcreditapplication.office = :office")->setParameter('office', $this->getOffice());
            }
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('WelfareBundle:MicroCredit:index.html.twig', array(
            'datatable' => $datatable,
            'meetingType' => 'micro-credit',
            'meetingForm' => $this->createMeetingForm(MicroCreditApplication::class),
            'createButton' => $this->get(MicroCreditManager::class)->hasNominatedApplications()
        ));
    }

    /**
     * @Security("is_granted('ROLE_DASB_CLERK')")
     * @Route("/welfare/micro-credit/create", name="welfare_micro_credit_create")
     */
    public function createAction(Request $request)
    {
        $serviceId = $request->query->get('service-id');

        if (!$serviceId) {
            return $this->renderCreateView(['errorMessage' => ''], $serviceId);
        }

        $manager = $this->get(MicroCreditManager::class);
        $eligibleInfo = $manager->eligibleInfo($serviceId, $this->getOffice());
        if (!$eligibleInfo['isEligible']) {
            return $this->renderCreateView($eligibleInfo, $serviceId);
        }

        $microCreditApplication = new MicroCreditApplication();
        $microCreditApplication->setServiceMan($eligibleInfo['data']['personnel']);
        $microCreditApplication->setNote($this->renderView('@Welfare/MicroCredit/applications/form.html.twig', $eligibleInfo['data']));
        $form = $this->createForm(MicroCreditApplicationForm::class, $microCreditApplication);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $manager->initApplication($microCreditApplication);
                return $this->redirectToRoute('welfare_micro_credit_index');
            }
        }

        $eligibleInfo['form'] = $form->createView();
        return $this->renderCreateView($eligibleInfo, $serviceId);
    }

    private function renderCreateView($data, $serviceId = null, $application = null)
    {
        $data['serviceId'] =  $serviceId;
        $data['application'] =  $application;
        return $this->render('WelfareBundle:MicroCredit:create.html.twig', $data);
    }

    /**
     * @Security("is_granted('edit:welfare_micro_credit_application', microCreditApplication)")
     * @Route("/welfare/micro-credit/edit/{id}", name="welfare_micro_credit_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, MicroCreditApplication $microCreditApplication)
    {
        if ($microCreditApplication->getOffice() != $this->getOffice()) {
            throw $this->createAccessDeniedException();
        }

        $manager = $this->get(MicroCreditManager::class);
        $form = $this->createForm(MicroCreditApplicationForm::class, $microCreditApplication);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $manager->initApplication($microCreditApplication);
                return $this->redirectToRoute('welfare_micro_credit_index');
            }
        }

        return $this->renderCreateView(['form' => $form->createView(), 'isEligible' => true], null, $microCreditApplication);

    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/micro-credit/view/{id}", name="welfare_micro_credit_view")
     */
    public function viewAction(MicroCreditApplication $application)
    {
        $data['application'] = $application;
        $data['personnel'] = $application->getServiceMan();
        $data['familyMembers'] = $data['personnel']->getFamilies();
        $data['selfApplicant'] = (strtolower($application->getApplicant()) == 'self') ? true :false;
        $data['spouseInfo'] = $data['personnel']->getSpouse();

        return $this->render('WelfareBundle:MicroCredit:view.html.twig', $data);
    }

    /**
     * @Security("is_granted('ROLE_BOARD_MEMBER')")
     * @Route("/board-meeting/micro-credit/view/{id}", name="board_meeting_micro_credit_view")
     */
    public function meetingViewAction(MicroCreditApplication $application)
    {
        return $this->viewAction($application);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/micro-credit/loan-recipients", name="welfare_micro_credit_loan_recipients")
     * @param Request $request
     * @return DatatableInterface|Response
     */
    public function loanRecipientsAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(MicroCreditLoanRecipientDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("microcreditapplication.grantStatus = :grantStatus")->setParameter('grantStatus', 'granted');
            if ($this->getOffice() && strtolower($this->getOffice()->getOfficeType()->getName()) == 'dasb') {
                $qb->andWhere("microcreditapplication.office = :office")->setParameter('office', $this->getOffice());
            }
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('WelfareBundle:MicroCredit:loan_recipients.html.twig', array(
            'datatable' => $datatable,
            'meetingType' => 'micro-credit',
            'meetingForm' => null,
            'createButton' => false
        ));
    }
}
