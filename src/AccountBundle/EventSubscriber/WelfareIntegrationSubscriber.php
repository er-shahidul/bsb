<?php

namespace AccountBundle\EventSubscriber;

use AccountBundle\Event\AccountIntegrationEvent;
use BoardMeetingBundle\Entity\BoardMeeting;
use BoardMeetingBundle\Event\BoardMeetingEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use WelfareBundle\Entity\BaseWelfareApplication;
use WelfareBundle\Entity\BoardRecommendation;

class WelfareIntegrationSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var EventDispatcherInterface */
    protected $dispatcher;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher)
    {
        $this->em = $entityManager;
        $this->dispatcher = $dispatcher;
    }

    public static function getSubscribedEvents()
    {
        return array(
            BoardMeetingEvent::CLOSE_BOARD_MEETING => array(
                array('applicationMeetingClose', 5),
            ),
            'workflow.welfare_board_recommendation.entered.completed' => array(
                array('grantRecommendedApplications', 9),
            ),
        );
    }

    public function applicationMeetingClose(BoardMeetingEvent $event)
    {
        /** @var BoardMeeting $entity */
        $entity = $event->getEntity();

        if (!$entity instanceof BoardMeeting) return;

        if ($entity->getType() !== 'bscr') return;

        $applications = $this->em->getRepository('WelfareBundle:WelfareApplication')->findBy(array('meeting' => $entity));
        if (empty($applications)) {
            $event->stopPropagation();
            $event->setError('No application found');
            return;
        }

        $welfareType = strtoupper($entity->getType());
        $vouchers = $this->generateVouchers($this->getValidApplications($applications), $welfareType);
        $sanction = AccountIntegrationEvent::sanctionFactory($welfareType . ' Cheque Issue', 'Summary of ' . $entity->getSubject());
        $sanction->setVouchers($vouchers);

        $event = new AccountIntegrationEvent($sanction, $entity->getOffice());

        $this->dispatcher->dispatch(AccountIntegrationEvent::ACCOUNT_MAKE_PAYMENT_EVENT, $event);
    }

    protected function getValidApplications($applications)
    {
        $dasbAppList = [];
        /** @var BaseWelfareApplication $application */
        foreach ($applications as $application) {
            if ($application->getAmount() > 0) {
                $dasbAppList[$application->getOffice()->getName()][] = $application;
            }
        }

        return $dasbAppList;
    }

    protected function generateVouchers($dasbAppList, $welfareType)
    {
        $vouchers = [];
        /** @var BaseWelfareApplication $application */
        foreach ($dasbAppList as $officeName => $applications) {
            $total = 0;

            foreach ($applications as $application) {
                $total += $application->getAmount();
            }

            $vouchers[$application->getOffice()->getName()] = AccountIntegrationEvent::voucherFactory($total, $officeName, $welfareType);
        }

        return $vouchers;
    }

    public function grantRecommendedApplications(Event $event) {
        /** @var BoardRecommendation $recommendation  */
        $recommendation = $event->getSubject();

        if (!$recommendation instanceof BoardRecommendation) {
            return;
        }

        $meeting = $recommendation->getMeeting();
        $applications = $this->em->getRepository('WelfareBundle:WelfareApplication')->findBy(array(
            'meeting' => $meeting, 'grantStatus' => 'granted'));

        if (empty($applications)) {
            $event->stopPropagation();
            return;
        }

        $welfareType = strtoupper($meeting->getType());
        $vouchers = $this->generateVouchers($this->getValidApplications($applications), $welfareType);
        $sanction = AccountIntegrationEvent::sanctionFactory($welfareType . ' Cheque Issue', 'Summary of ' . $meeting->getSubject());
        $sanction->setVouchers($vouchers);

        $accountIntegrationEvent = new AccountIntegrationEvent($sanction, $meeting->getOffice());

        $this->dispatcher->dispatch(AccountIntegrationEvent::ACCOUNT_MAKE_PAYMENT_EVENT, $accountIntegrationEvent);

        $this->em->flush();
    }
}