<?php

namespace WelfareBundle\Listener;

use BoardMeetingBundle\Entity\BoardMeeting;
use BoardMeetingBundle\Event\BoardMeetingEvent;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Workflow\Registry;
use WelfareBundle\Entity\BoardRecommendation;
use WelfareBundle\Entity\BSCRApplication;

class BoardMettingSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;
    protected $dispatcher;
    /**
     * @var Registry
     */
    private $workflow;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher, Registry $workflow)
    {
        $this->em = $entityManager;
        $this->dispatcher = $dispatcher;
        $this->workflow = $workflow;
    }

    public static function getSubscribedEvents()
    {
        return array(
            BoardMeetingEvent::BOARD_MEETING_BEFORE_CREATE => array(
                array('beforeCreateBoardMeeting', 10),
            ),
            BoardMeetingEvent::BOARD_MEETING_CREATED=> array(
                array('afterMeetingCreated', 10),
            ),
            'workflow.view.event' => array(
                array('boardMeetingView', 10),
                array('recommendedBoardMeetingView', 10),
            ),
            BoardMeetingEvent::ATTEND_BOARD_MEETING => array(
                array('boardMeetingMemberView', 10),
            ),
            BoardMeetingEvent::CLOSE_BOARD_MEETING => array(
                array('applicationMeetingClose', 10),
            ),
        );
    }

    public function beforeCreateBoardMeeting(BoardMeetingEvent $event)
    {
        $entity = $event->getEntity();

        if ($entity->getType() !== 'bscr' && $entity->getType() !== 'rcel' && $entity->getType() !== 'micro-credit') return;

        $nominatedApplications = $this->getNominatedApplications($entity->getType());

        if (empty($nominatedApplications)) {
            $event->stopPropagation();
            $event->setError('Nominated application not found. Try again later');
        }
    }

    public function afterMeetingCreated(BoardMeetingEvent $event)
    {
        $entity = $event->getEntity();

        if ($entity->getType() !== 'bscr'  && $entity->getType() !== 'rcel' && $entity->getType() !== 'micro-credit') return;

        $applications = $this->getNominatedApplications($entity->getType());

        /** @var BSCRApplication $application */
        foreach ($applications as $application) {
            $application->setMeeting($entity);
            $application->setStatus('wait_for_board_member_grant');
        }
        $this->em->flush();
    }

    private function getNominatedApplications($type) {

        switch ($type) {
            case 'rcel':
                $repo = $this->em->getRepository('WelfareBundle:RCELApplication');
                break;
            case 'micro-credit':
                $repo = $this->em->getRepository('WelfareBundle:MicroCreditApplication');
                break;
            case 'bscr':
            default:
                $repo = $this->em->getRepository('WelfareBundle:BSCRApplication');
                break;
        }
        return $repo->findBy(array('status' => 'nominated'));
    }

    public function boardMeetingView(GetResponseWorkflowEvent $event)
    {
        $entity = $event->getEntity();

        if (!$entity instanceof BoardMeeting) return;

        $repo = $this->em->getRepository('WelfareBundle:WelfareApplication');
        $applications = $repo->findBy(array('meeting' => $entity->getId()), array('createdAt' => 'asc'));

        $applicationType = !empty($applications[0]) ? $applications[0]->getType() : '';

        $builder = new ResponseBuilderData('@Welfare/BoardMeeting/meeting.html.twig', [
            'meeting' => $entity,
            'applications' => $applications,
            'dateRange' => $this->getApplicationDateRange($applications),
            'applicationCount' => count($applications),
            'applicationPath' => $this->fixPath('welfare_'.$applicationType.'_view'),
        ]);

        $event->setResponseBuilder($builder);
    }

    public function recommendedBoardMeetingView(GetResponseWorkflowEvent $event)
    {
        /** @var BoardRecommendation $entity */
        $boardRecommendation = $event->getEntity();

        if (!$boardRecommendation instanceof BoardRecommendation) return;

        $repo = $this->em->getRepository('WelfareBundle:WelfareApplication');
        $applications = $repo->findBy(array('meeting' => $boardRecommendation->getMeeting(), 'status' => 'recommended'), array('createdAt' => 'asc'));

        $applicationType = !empty($applications[0]) ? $applications[0]->getType() : '';

        $builder = new ResponseBuilderData('@Welfare/BoardMeeting/recommended_meeting.html.twig', [
            'boardRecommendation' => $boardRecommendation,
            'meeting' => $boardRecommendation->getMeeting(),
            'applications' => $applications,
            'dateRange' => $this->getApplicationDateRange($applications),
            'applicationCount' => count($applications),
            'applicationPath' => $this->fixPath('welfare_'.$applicationType.'_view'),
        ]);

        $event->setResponseBuilder($builder);
    }
    
    public function boardMeetingMemberView(BoardMeetingEvent $event)
    {
        /** @var BoardMeeting $entity */
        $entity = $event->getEntity();

        if (!$entity instanceof BoardMeeting) return;

        $repo = $this->em->getRepository('WelfareBundle:WelfareApplication');

        $applications = $repo->findBy(array('meeting' => $entity->getId()), array('createdAt' => 'asc'));

        $applicationType = !empty($applications[0]) ? $applications[0]->getType() : '';

        $builder = new ResponseBuilderData('WelfareBundle:BoardMeeting:meeting_grant.html.twig', [
            'meeting' => $entity,
            'applications' => $applications,
            'dateRange' => $this->getApplicationDateRange($applications),
            'applicationCount' => count($applications),
            'applicationPath' => $this->fixPath('board_meeting_'.$applicationType.'_view'),
            'meetingClosed' => strtolower($entity->getStatus()) == 'closed',
            'commentable' => true
        ]);

        $event->setResponseBuilder($builder);
    }
    
    private function fixPath($path) {
        return str_replace("-", "_", $path);
    }

    private function getApplicationDateRange($applications) {

        if (empty($applications)) {
            return ['start' => '', 'end' => ''];
        }

        $firstApplication = $applications[0];
        $lastApplication = $applications[count($applications)-1];

        return array(
            'start' => $firstApplication->getCreatedAt(),
            'end' => $lastApplication->getCreatedAt()
        );
    }

    public function applicationMeetingClose(BoardMeetingEvent $event)
    {
        /** @var BoardMeeting $entity */
        $entity = $event->getEntity();

        if (!$entity instanceof BoardMeeting) return;

        $applications = $this->em->getRepository('WelfareBundle:WelfareApplication')->findBy(array('meeting' => $entity));
        if (empty($applications)) {
            $event->stopPropagation();
            $event->setError('No application found');
            return;
        }

        if ($entity->getType() == 'rcel' || $entity->getType() == 'micro-credit') {
            $boardRecommendation = new BoardRecommendation($entity);
            $boardRecommendation->setOffice($entity->getOffice());
            $this->em->persist($boardRecommendation);
            $this->workflow->get($boardRecommendation)->apply($boardRecommendation, 'initialize');
        }

        foreach ($applications as $application) {
            if ($application->getAmount() > 0) {

                $this->dispatcher->dispatch('granted_application', new GenericEvent($application));
                continue;
            }
            $application->setStatus('completed');
            $application->setGrantStatus('not_granted');
        }
        $this->em->flush();
    }
}
