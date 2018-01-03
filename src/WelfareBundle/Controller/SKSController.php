<?php

namespace WelfareBundle\Controller;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WelfareBundle\Datatables\SKSApplicationDatatable;
use WelfareBundle\Entity\SKSApplication;
use WelfareBundle\Form\SKSApplicationForm;
use WelfareBundle\Listener\SKSWorkflowSubscriber;
use WelfareBundle\Manager\SKSManager;
use WelfareBundle\Manager\WelfareManager;

class SKSController extends BaseController
{
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/sks", name="welfare_sks_index")
     * @param Request $request
     * @return DatatableInterface|Response
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(SKSApplicationDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice() && strtolower($this->getOffice()->getOfficeType()->getName()) == 'dasb') {
                $qb->andWhere("sksapplication.office = :office");
                $qb->setParameter('office', $this->getOffice());
            }
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('WelfareBundle:SKS:index.html.twig', array(
            'datatable' => $datatable,
            'meetingType' => 'sks',
            'meetingForm' => $this->createMeetingForm(SKSApplication::class)
        ));
    }

    /**
     * @Security("is_granted('ROLE_DASB_CLERK')")
     * @Route("/welfare/sks/create", name="welfare_sks_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $serviceId = $request->query->get('service-id');
        $type = urldecode($request->query->get('type'));
        $type = $this->getDoctrine()->getRepository('WelfareBundle:SKSApplicationType')->findOneBy(['id' => $type]);

        if (!$serviceId) {
            return $this->renderCreateView(['errorMessage' => ''], $serviceId, $type);
        }

        $manager = $this->get(SKSManager::class);
        $eligibleInfo = $manager->eligibleInfo($type, $serviceId, $this->getOffice());
        if (!$eligibleInfo['isEligible']) {
            return $this->renderCreateView($eligibleInfo, $serviceId, $type);
        }

        $sksApplication = new SKSApplication();
        $sksApplication->setType($type);
        $sksApplication->setServiceMan($eligibleInfo['data']['personnel']);
        $sksApplication->setNote($this->renderView('@Welfare/SKS/applications/veteran_allowance.html.twig', $eligibleInfo['data']));
        $form = $this->createForm(SKSApplicationForm::class, $sksApplication);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $manager->initApplication($sksApplication);
                return $this->redirectToRoute('welfare_sks_index');
            }
        }

        $eligibleInfo['form'] = $form->createView();
        return $this->renderCreateView($eligibleInfo, $serviceId, $type);
    }

    private function renderCreateView($data, $serviceId = null, $type = null, $application = null)
    {
        $data['serviceId'] =  $serviceId;
        $data['type'] =  $type;
        $data['application'] =  $application;
        $data['sksTypes'] = $this->getDoctrine()->getRepository('WelfareBundle:SKSApplicationType')->findAll();
        return $this->render('WelfareBundle:SKS:create.html.twig', $data);
    }

    /**
     * @Security("is_granted('edit:welfare_sks_application', sksApplication)")
     * @Route("/welfare/sks/edit/{id}", name="welfare_sks_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, SKSApplication $sksApplication)
    {
        if ($sksApplication->getOffice() != $this->getOffice()) {
            throw $this->createAccessDeniedException();
        }

        $manager = $this->get(SKSManager::class);
        $form = $this->createForm(SKSApplicationForm::class, $sksApplication);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $manager->initApplication($sksApplication);
                return $this->redirectToRoute('welfare_sks_index');
            }
        }

        return $this->renderCreateView(['form' => $form->createView(), 'isEligible' => true], null, null, $sksApplication);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/sks/view/{id}", name="welfare_sks_view")
     */
    public function viewAction(SKSApplication $application)
    {
        $data['application'] = $application;
        $data['personnel'] = $application->getServiceMan();
        $data['familyMembers'] = $data['personnel']->getFamilies();
        $data['selfApplicant'] = (strtolower($application->getApplicant()) == 'self') ? true :false;
        $data['spouseInfo'] = $data['personnel']->getSpouse();

        return $this->render('WelfareBundle:SKS:view.html.twig', $data);
    }

}
