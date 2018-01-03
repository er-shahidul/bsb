<?php

namespace BoardMeetingBundle\Controller;

use AppBundle\Controller\BaseController;
use BoardMeetingBundle\Datatables\MicroCreditBoardMeetingDatatable;
use BoardMeetingBundle\Datatables\WelfareBoardMeetingDatatable;
use BoardMeetingBundle\Entity\BoardMeeting;
use BoardMeetingBundle\Entity\BoardMember;
use BoardMeetingBundle\Event\BoardMeetingEvent;
use BoardMeetingBundle\Form\BoardMeetingType;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends BaseController
{
    /**
     * @Route("/list-board-meeting/welfare/", name="board_meetings_welfare")
     */
    public function boardMeetingsWelfareAction(Request $request)
    {
        $datatable = $this->prepareDatatable(WelfareBoardMeetingDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
                $qb->andWhere("boardmeeting.type != :type")->setParameter('type', 'micro-credit');
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@BoardMeeting/Default/index.html.twig', array(
            'datatable' => $datatable,
            'pageTitle' => 'Welfare Board Meeting'
        ));
    }

    /**
     * @Route("/list-board-meeting/micro-credit", name="board_meetings_micro_credit")
     */
    public function boardMeetingsMicroCreditAction(Request $request)
    {
        $datatable = $this->prepareDatatable(MicroCreditBoardMeetingDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("boardmeeting.type = :type")->setParameter('type', 'micro-credit');
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@BoardMeeting/Default/index.html.twig', array(
            'datatable' => $datatable,
            'pageTitle' => 'Micor-credit Board Meeting'
        ));
    }

    /**
     * @Route("/create-board-meeting/{type}", name="board_meeting_create")
     * @Method({"POST"})
     * @param Request         $request
     * @param                 $type
     * @Security("has_role('ROLE_WELFARE_CLERK')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction($type, Request $request)
    {
        $meeting = new BoardMeeting();

        $form = $this->createForm(BoardMeetingType::class, $meeting);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $meeting->setType($type);
            $meeting->setOffice($this->getOffice());
            $event = new BoardMeetingEvent($meeting);
            $this->dispatch(BoardMeetingEvent::BOARD_MEETING_BEFORE_CREATE, $event);

            if ($event->isPropagationStopped()) {
                return new JsonResponse(['success' => FALSE, 'msg' => $event->getError()]);
            }

            $this->updateChairman($meeting);

            $this->getDoctrine()->getManager()->persist($meeting);
            $this->getDoctrine()->getManager()->flush($meeting);

            $this->dispatch(BoardMeetingEvent::BOARD_MEETING_CREATED, $event);

            return new JsonResponse([
                'success' => TRUE,
                'url' => $this->generateUrl('board_meeting_view', ['id'=> $meeting->getId()]),
                'msg' => 'Meeting created Successfully'
            ]);
        }

        return new JsonResponse(['success' => FALSE, 'msg' => 'Unknown error!']);
    }

    /**
     * Displays a form to edit an existing serviceman entity.
     *
     * @Route("/{id}/edit-board-meeting", name="board_meeting_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_WELFARE_CLERK') and is_granted('edit:board_meeting', meeting)")
     * @param Request      $request
     * @param BoardMeeting $meeting
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, BoardMeeting $meeting)
    {
        $editForm = $this->createForm(BoardMeetingType::class, $meeting);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->updateChairman($meeting);

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('board_meeting_view', ['id' => $meeting->getId()]);
        }

        return $this->render('@BoardMeeting/Default/form.html.twig', array(
            'pageTitle' => 'Edit Board Information',
            'form' => $editForm->createView()
        ));
    }

    /**
     * @Route("/view-board-meeting/{id}", name="board_meeting_view")
     * @param BoardMeeting $meeting
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewMeeting(BoardMeeting $meeting)
    {
        $event = new GetResponseWorkflowEvent($meeting);

        $this->get('event_dispatcher')->dispatch('workflow.view.event', $event);

        if ($event->getResponse() == null && $event->getResponseBuilder() == null) {
            throw $this->createNotFoundException();
        }

        if($event->getResponse() == null) {
            $data = $event->getResponseBuilder()->getData();
            return $this->render($event->getResponseBuilder()->getView(), $data);
        }

        return $event->getResponse();
    }

    /**
     * @param BoardMeeting $meeting
     */
    private function updateChairman(BoardMeeting $meeting)
    {
        foreach ($meeting->getMembers() as $member) {
            /** @var BoardMember $member */
            if ($member->isChairman()) {
                $meeting->setChairman($member);
            }
        }
    }
}
