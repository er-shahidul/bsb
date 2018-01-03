<?php

namespace WelfareBundle\Listener;

use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Templating\EngineInterface;
use WelfareBundle\Entity\BSCRApplication;
use WelfareBundle\Entity\WelfareApplication;
use WelfareBundle\Manager\BSCRManager;

class BSCRWorkflowSubscriber extends WelfareWorkflowSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;
    protected $manager;
    protected $twigEngine;
    protected $token;

    public function __construct(EntityManagerInterface $entityManager, BSCRManager $manager, EngineInterface $twigEngine, TokenStorageInterface $token)
    {
        parent::__construct($entityManager);
        $this->em = $entityManager;
        $this->manager = $manager;
        $this->twigEngine = $twigEngine;
        $this->token = $token;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.view.event' => array(
                array('applicationViewBSCR', 10),
            ),
            'granted_application' => array(
                array('grantApplication', 10),
            ),
            'workflow_step_remark_save' => array(
                array('attachRemarks', 10)
            )
        );
    }

    public function attachRemarks(GenericEvent $event) {

        /** @var BSCRApplication $entity */
        $entity = $event->getSubject();

        if (!$entity instanceof BSCRApplication) {
            return;
        }

        $place = $event->getArgument('place');
        if (!in_array($place, ['wait_for_io', 'wait_for_secretary', 'wait_for_ao', 'wait_for_dd', 'wait_for_director'])) {
            return;
        }

        $appData = $entity->getApplicationData();
        $info = $event->getArgument('remarkInfo');

        switch ($place) {
            case 'wait_for_io':
                $appData['extra']['ioRemark'] = $info;
                break;
            case 'wait_for_secretary':
                $appData['extra']['secretaryRemark'] = $info;
                break;
            case 'wait_for_ao':
                $appData['extra']['aoRemark'] = $info;
                break;
            case 'wait_for_dd':
                $appData['extra']['ddRemark'] = $info;
                break;
            case 'wait_for_director':
                $appData['extra']['directorRemark'] = $info;
                break;
            default:
        }

        $entity->setApplicationData($appData);
    }

    public function applicationViewBSCR(GetResponseWorkflowEvent $event)
    {
        if (!$event->getEntity() instanceof BSCRApplication) {
            return;
        }

        $application = $event->getEntity();

        $data['application'] = $application;
        $data['formTemplate'] = $this->twigEngine->render('@Welfare/BSCR/applications/form_view.html.twig', [
            'formData' => $this->manager->prepareFormData($application->getApplicationData(), $this->token->getToken()->getRoles()),
            'application' => $application
        ]);

        $builder = new ResponseBuilderData('WelfareBundle:BSCR:view.html.twig', $data);
        $event->setResponseBuilder($builder);
    }

    public function grantApplication(GenericEvent $event) {
        /** @var WelfareApplication $application  */
        $application = $event->getSubject();

        if (!$application instanceof BSCRApplication) {
            return;
        }

        $application->setGrantStatus('granted');
        $application->setGrantedAt(new \DateTime());
        $application->setStatus('completed');

        //$this->createBSCRFundReceivedEntryForPersonnel($application);
    }

    /**
     * @param $application
     */
    private function createBSCRFundReceivedEntryForPersonnel($application)
    {
        $fundType = $this->em->getReference('PersonnelBundle:WelfareFund', 'Bangladesh Serviceman Charitable Relief Fund (BSCR)');
        $this->createFundReceivedEntryForPersonnel($fundType, $application);
    }
}