<?php

namespace BoardMeetingBundle\Controller;

use BoardMeetingBundle\Entity\BoardMember;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/board-meeting")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/authenticate/{member}/{_token}", name="board_meeting_authenticate")
     * @param Request     $request
     * @param BoardMember $member
     * @param             $_token
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function authenticateAction(Request $request, BoardMember $member, $_token)
    {
        $meeting = $member->getMeeting();

        if(!in_array($meeting->getStatus(), ['approved', 'closed'])) {
            throw $this->createNotFoundException();
        }

        $secret = $this->getParameter('secret') . $member->getId() . $member->getMeeting()->getId();

        try {
            JWT::decode($_token, $secret, array('HS256'));
        } catch (BeforeValidException $e) {
            return $this->createErrorPage(403, sprintf('Meeting will be accessible from %s', $meeting->getDate()->format('jS M, Y')));
        } catch (\Exception $exception) {
            throw $this->createNotFoundException();
        }

        $request->query->set('_token', $_token);

        return $this->redirect($this->generateUrl('board_meeting_attend'));
    }

    public function isValid($secret, $token)
    {
        try {
            return JWT::decode($token, $secret, array('HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }

    private function createErrorPage($status, $message)
    {
        $response = new Response('', $status);
        return $this->render('@BoardMeeting/Errors/not-started.html.twig', [
            'status_code' => 403,
            'message' => $message
        ], $response);
    }
}
