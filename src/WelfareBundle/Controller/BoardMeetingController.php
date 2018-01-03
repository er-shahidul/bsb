<?php

namespace WelfareBundle\Controller;

use BoardMeetingBundle\Entity\BoardMember;
use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WelfareBundle\Entity\WelfareApplication;
use WelfareBundle\Form\MeetingApplicationCommentForm;
use WelfareBundle\Manager\BSCRManager;
use WelfareBundle\Manager\MicroCreditManager;
use WelfareBundle\Manager\RCELManager;

/**
 * @Route("/board-meeting")
 */
class BoardMeetingController extends \AppBundle\Controller\BaseController
{
    /**
     * @Route("/member/application-comments/{application}", name="welfare_member_application_comments")
     * @param Request             $request
     * @param WelfareApplication  $application
     * @Security("has_role('ROLE_BOARD_MEMBER')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function applicationCommentsAction(Request $request, WelfareApplication $application)
    {
        $boardMember = $this->getRepository('BoardMeetingBundle:BoardMember')->find($this->getUser()->getId());

        $allowedMember = $this->getDoctrine()->getRepository('WelfareBundle:WelfareApplication')->findOneBy([
            'id' => $application->getId(), 'meeting' => $boardMember->getMeeting()]);

        if (!$allowedMember) {
            return new Response('Access Denied');
        }

        if (strtolower($application->getMeeting()->getStatus()) == 'closed') {
            return $this->render('WelfareBundle:BoardMeeting:application_comments.html.twig', [
                'application' => $application,
                'policyText' => $this->policyText($application)
            ]);
        }

        $comments = $application->getMemberComments();

        $defaults = ['application' => $application, 'member' => $boardMember, 'isChairman' => $boardMember->isChairman()];
        if ($application->getType() == 'micro-credit') {
            $defaults['installmentAmount'] = $this->get(PolicyManager::class)->getPolicyValue('welfare.micro_credit_per_installment_amount', Type::INTEGER);
        }

        $form = $this->createForm(MeetingApplicationCommentForm::class, $application, $defaults);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->updateApplicationComment($application, $boardMember, $comments, $form->get('memberComments')->getData());
            return new Response('success');
        }

        return $this->render('WelfareBundle:BoardMeeting:application_comments.html.twig', [
            'form' => $form->createView(),
            'application' => $application,
            'policyText' => $this->policyText($application)
        ]);
    }

    private function policyText(WelfareApplication $application) {
        switch ($application->getType()) {
            case 'rcel':
                return $this->get(RCELManager::class)->getGrantPolicyText();
            case 'micro-credit':
                return $this->get(MicroCreditManager::class)->getGrantPolicyText();
            case 'bscr':
                return $this->get(BSCRManager::class)->getGrantPolicyText();
        }
    }

    private function updateApplicationComment(WelfareApplication $application, BoardMember $boardMember, $comments, $comment) {

        $now = new \DateTime();
        $comments[$boardMember->getId()] = [
            'comment' => $comment,
            'on' => $now->format('Y-m-d H:i:s'),
            'by' => [
                'name' => $boardMember->getName(),
                'designation' => $boardMember->getDesignation(),
                'mobileNo' => $boardMember->getMobileNo()
            ]
        ];

        $application->setMemberComments($comments);
        $this->getDoctrine()->getManager()->flush();
    }
}
