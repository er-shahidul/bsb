<?php

namespace WelfareBundle\Listener;

use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Templating\EngineInterface;
use WelfareBundle\Entity\BoardRecommendation;
use WelfareBundle\Entity\RCELApplication;
use WelfareBundle\Manager\RCELManager;

class RCELWorkflowSubscriber extends WelfareWorkflowSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;
    protected $manager;
    protected $twigEngine;
    protected $token;

    public function __construct(EntityManagerInterface $entityManager, RCELManager $manager, EngineInterface $twigEngine, TokenStorageInterface $token)
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
                array('applicationViewRCEL', 10),
            ),
            'granted_application' => array(
                array('recommendApplication', 10),
            ),
            'workflow.welfare_board_recommendation.entered.completed' => array(
                array('grantRecommendedApplications', 10),
            ),
            'workflow_step_remark_save' => array(
                array('attachRemarks', 10)
            )
        );
    }

    public function attachRemarks(GenericEvent $event) {

        /** @var RCELApplication $entity */
        $entity = $event->getSubject();

        if (!$entity instanceof RCELApplication) {
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

    public function applicationViewRCEL(GetResponseWorkflowEvent $event)
    {
        if (!$event->getEntity() instanceof RCELApplication) {
            return;
        }

        $application = $event->getEntity();

        $data['application'] = $application;
        $data['formTemplate'] = $this->twigEngine->render('@Welfare/RCEL/applications/form_view.html.twig', [
            'formData' => $this->manager->prepareFormData($application->getApplicationData(), $this->token->getToken()->getRoles()),
            'application' => $application
        ]);

        $builder = new ResponseBuilderData('WelfareBundle:RCEL:view.html.twig', $data);
        $event->setResponseBuilder($builder);
    }

    public function recommendApplication(GenericEvent $event) {
        /** @var WelfareApplication $application  */
        $application = $event->getSubject();

        if (!$application instanceof RCELApplication) {
            return;
        }

        $application->setStatus('recommended');
    }

    public function grantRecommendedApplications($event) {
        /** @var BoardRecommendation $recommendation  */
        $recommendation = $event->getSubject();

        if (!$recommendation instanceof BoardRecommendation) {
            return;
        }

        $meeting = $recommendation->getMeeting();
        $applications = $this->em->getRepository('WelfareBundle:RCELApplication')->findBy(array(
            'meeting' => $meeting, 'status' => 'recommended'));

        foreach ($applications as $application) {
            $application->setGrantStatus('granted');
            $application->setGrantedAt(new \DateTime());
            $application->setStatus('completed');

            //$this->createRCELFundReceivedEntryForPersonnel($application);
        }
        $this->em->flush();
    }

    /**
     * @param $application
     */
    private function createRCELFundReceivedEntryForPersonnel($application)
    {
        $fundType = $this->em->getReference('PersonnelBundle:WelfareFund', 'Royal Commonwealth Ex-services League (RCEL)');
        $this->createFundReceivedEntryForPersonnel($fundType, $application);
    }
}