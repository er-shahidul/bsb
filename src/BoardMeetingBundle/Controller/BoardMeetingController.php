<?php

namespace BoardMeetingBundle\Controller;

use BoardMeetingBundle\Entity\BoardMeeting;
use BoardMeetingBundle\Event\BoardMeetingEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/board-meeting")
 */
class BoardMeetingController extends Controller
{
    /**
     * @Route("/attend", name="board_meeting_attend")
     * @Security("has_role('ROLE_BOARD_MEMBER')")
     * @return Response
     */
    public function attendAction()
    {
        $meeting = $this->getUser()->getMeeting();

        $event = new BoardMeetingEvent($meeting);

        $this->get('event_dispatcher')->dispatch(BoardMeetingEvent::ATTEND_BOARD_MEETING, $event);

        if ($event->getResponse() == null && $event->getResponseBuilder() == null) {
            throw $this->createNotFoundException();
        }

        if($event->getResponse() == null) {
            $data = $event->getResponseBuilder()->getData();
            $data['boardMemberId'] = $this->getUser()->getId();
            $data['isChairman'] = $this->getUser()->isChairman();

            return $this->render($event->getResponseBuilder()->getView(), $data);
        }

        return $event->getResponse();
    }

    /**
     * @Route("/close", name="board_meeting_close")
     * @Security("has_role('ROLE_CHAIRMAN')")
     * @Method({"POST"})
     * @param Request $request
     *
     * @return Response
     */
    public function closeAction(Request $request)
    {
        /** @var BoardMeeting $meeting */
        $meeting = $this->getUser()->getMeeting();

        if(!$this->isCsrfTokenValid('meeting'.$meeting->getId(), $request->request->get('_csrf_token'))) {
            throw $this->createAccessDeniedException();
        }

        $event = new BoardMeetingEvent($meeting);

        $this->get('event_dispatcher')->dispatch(BoardMeetingEvent::CLOSE_BOARD_MEETING, $event);

        if ($event->isPropagationStopped()) {
            $this->addFlash('error', $event->getError());
        } else {
            $meeting->setStatus('closed');
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('board_meeting_attend');
    }
}
