<?php

namespace WelfareBundle\Controller;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WelfareBundle\Datatables\RCELApplicationDatatable;
use WelfareBundle\Entity\RCELApplication;
use WelfareBundle\Form\RCELApplicationForm;
use WelfareBundle\Manager\RCELManager;

class RCELController extends BaseController
{
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/rcel", name="welfare_rcel_index")
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(RCELApplicationDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice() && strtolower($this->getOffice()->getOfficeType()->getName()) == 'dasb') {
                $qb->andWhere("rcelapplication.office = :office");
                $qb->setParameter('office', $this->getOffice());
            }
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('WelfareBundle:RCEL:index.html.twig', array(
            'datatable' => $datatable,
            'meetingType' => 'rcel',
            'meetingForm' => $this->createMeetingForm(RCELApplication::class),
            'createButton' => $this->get(RCELManager::class)->hasNominatedApplications()
        ));
    }

    /**
     * @Security("is_granted('ROLE_DASB_CLERK')")
     * @Route("/welfare/rcel/create", name="welfare_rcel_create")
     */
    public function createAction(Request $request)
    {
        $serviceId = $request->query->get('service-id');

        if (!$serviceId) {
            return $this->renderTemplate(['errorMessage' => '']);
        }

        $manager = $this->get(RCELManager::class);
        $eligibleInfo = $manager->eligibleInfo($serviceId, $this->getOffice());
        if (!$eligibleInfo['isEligible']) {
            return $this->renderTemplate($eligibleInfo);
        }

        $rcelApplication = new RCELApplication();
        $rcelApplication->setServiceMan($eligibleInfo['data']['personnel']);
        $form = $this->createForm(RCELApplicationForm::class, $rcelApplication);

        return $this->save($manager, $request, $form, $eligibleInfo, $rcelApplication);
    }

    private function save($manager, $request, $form, $eligibleInfo, $rcelApplication) {

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $defaultData = $manager->getDefaultFormData($rcelApplication);
                $rcelApplication->setApplicationData(array_merge_recursive($defaultData, $request->request->all()));
                $manager->initApplication($rcelApplication);
                return $this->redirectToRoute('welfare_rcel_index');
            }
        }

        return $this->renderCreateView($manager, $form, $eligibleInfo, $rcelApplication);
    }

    private function renderCreateView($manager, $form, $data, $application = null)
    {
        $data['form'] = $form->createView();
        $data['formTemplate'] = $this->renderView('@Welfare/RCEL/applications/form.html.twig', [
            'formData' => $manager->prepareFormData($manager->getDefaultFormData($application)),
            'form' => $data['form']
        ]);
        return $this->renderTemplate($data, $application);
    }

    private function renderTemplate($data, $application = null) {
        $data['application'] =  $application;
        return $this->render('WelfareBundle:RCEL:create.html.twig', $data);
    }

    /**
     * @Security("is_granted('edit:welfare_rcel_application', rcelApplication)")
     * @Route("/welfare/rcel/edit/{id}", name="welfare_rcel_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, RCELApplication $rcelApplication)
    {
        if ($rcelApplication->getOffice() != $this->getOffice()) {
            throw $this->createAccessDeniedException();
        }

        $manager = $this->get(RCELManager::class);
        $form = $this->createForm(RCELApplicationForm::class, $rcelApplication, ['extraData' => $rcelApplication->getApplicationData()['extra']]);

        $data['isEligible'] = true;
        return $this->save($manager, $request, $form, $data, $rcelApplication);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/rcel/view/{id}", name="welfare_rcel_view")
     */
    public function viewAction(RCELApplication $application)
    {
        $manager = $this->get(RCELManager::class);
        $data['application'] = $application;
        $data['formTemplate'] = $this->renderView('@Welfare/RCEL/applications/form_view.html.twig', [
            'formData' => $manager->prepareFormData($application->getApplicationData()),
            'application' => $application
        ]);
        return $this->render('WelfareBundle:RCEL:view.html.twig', $data);
    }

    /**
     * @Security("is_granted('ROLE_BOARD_MEMBER')")
     * @Route("/board-meeting/rcel/view/{id}", name="board_meeting_rcel_view")
     */
    public function meetingViewAction(RCELApplication $application)
    {
        return $this->viewAction($application);
    }
}
