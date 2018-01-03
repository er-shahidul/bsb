<?php

namespace WelfareBundle\Controller;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WelfareBundle\Datatables\BSCRApplicationDatatable;
use WelfareBundle\Entity\BSCRApplication;
use WelfareBundle\Form\BSCRApplicationForm;
use WelfareBundle\Manager\BSCRManager;

class BSCRController extends BaseController
{
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/bscr", name="welfare_bscr_index")
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(BSCRApplicationDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice() && strtolower($this->getOffice()->getOfficeType()->getName()) == 'dasb') {
                $qb->andWhere("bscrapplication.office = :office");
                $qb->setParameter('office', $this->getOffice());
            }
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('WelfareBundle:BSCR:index.html.twig', array(
            'datatable' => $datatable,
            'meetingType' => 'bscr',
            'meetingForm' => $this->createMeetingForm(BSCRApplication::class),
            'createButton' => $this->get(BSCRManager::class)->hasNominatedApplications()
        ));
    }

    /**
     * @Security("is_granted('ROLE_DASB_CLERK')")
     * @Route("/welfare/bscr/create", name="welfare_bscr_create")
     */
    public function createAction(Request $request)
    {
        $serviceId = $request->query->get('service-id');

        if (!$serviceId) {
            return $this->renderTemplate(['errorMessage' => '']);
        }

        $manager = $this->get(BSCRManager::class);
        $eligibleInfo = $manager->eligibleInfo($serviceId, $this->getOffice());
        if (!$eligibleInfo['isEligible']) {
            return $this->renderTemplate($eligibleInfo);
        }

        $bscrApplication = new BSCRApplication();
        $bscrApplication->setServiceMan($eligibleInfo['data']['personnel']);
        $form = $this->createForm(BSCRApplicationForm::class, $bscrApplication);

        return $this->save($manager, $request, $form, $eligibleInfo, $bscrApplication);
    }

    /**
     * @Security("is_granted('edit:welfare_bscr_application', bscrApplication)")
     * @Route("/welfare/bscr/edit/{id}", name="welfare_bscr_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, BSCRApplication $bscrApplication)
    {
        if ($bscrApplication->getOffice() != $this->getOffice()) {
            throw $this->createAccessDeniedException();
        }

        $manager = $this->get(BSCRManager::class);
        $form = $this->createForm(BSCRApplicationForm::class, $bscrApplication, ['extraData' => $bscrApplication->getApplicationData()['extra']]);

        $data['isEligible'] = true;
        return $this->save($manager, $request, $form, $data, $bscrApplication);
    }

    private function save($manager, $request, $form, $eligibleInfo, $bscrApplication) {

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $defaultData = $manager->getDefaultFormData($bscrApplication);
                $bscrApplication->setApplicationData(array_merge_recursive($defaultData, $request->request->all()));
                $manager->initApplication($bscrApplication);
                return $this->redirectToRoute('welfare_bscr_index');
            }
        }

        return $this->renderCreateView($manager, $form, $eligibleInfo, $bscrApplication);
    }

    private function renderCreateView($manager, $form, $data, $application = null)
    {
        $data['form'] = $form->createView();
        $data['formTemplate'] = $this->renderView('@Welfare/BSCR/applications/form.html.twig', [
            'formData' => $manager->prepareFormData($manager->getDefaultFormData($application)),
            'form' => $data['form']
        ]);
        return $this->renderTemplate($data, $application);
    }

    private function renderTemplate($data, $application = null) {
        $data['application'] =  $application;
        return $this->render('WelfareBundle:BSCR:create.html.twig', $data);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/bscr/view/{id}", name="welfare_bscr_view")
     */
    public function viewAction(BSCRApplication $application)
    {
        $manager = $this->get(BSCRManager::class);
        $data['application'] = $application;
        $data['formTemplate'] = $this->renderView('@Welfare/BSCR/applications/form_view.html.twig', [
            'formData' => $manager->prepareFormData($application->getApplicationData()),
            'application' => $application
            ]);
        return $this->render('WelfareBundle:BSCR:view.html.twig', $data);
    }

    /**
     * @Security("is_granted('ROLE_BOARD_MEMBER')")
     * @Route("/board-meeting/bscr/view/{id}", name="board_meeting_bscr_view")
     */
    public function meetingViewAction(BSCRApplication $application)
    {
        return $this->viewAction($application);
    }
}
