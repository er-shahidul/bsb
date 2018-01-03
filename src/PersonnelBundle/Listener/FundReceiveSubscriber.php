<?php

namespace WelfareBundle\Listener;

use BoardMeetingBundle\Entity\BoardMeeting;
use BoardMeetingBundle\Event\BoardMeetingEvent;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Doctrine\ORM\EntityManagerInterface;
use PersonnelBundle\Entity\FundReceived;
use PersonnelBundle\Entity\WelfareFund;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use WelfareBundle\Entity\BSCRApplication;
use WelfareBundle\Entity\WelfareApplication;

class FundReceiveSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.welfare_rcel_application.entered.completed' => array(
                array('RCELApplicationGrant', 11),
            ),

        );
    }

    public function RCELApplicationGrant(WelfareApplication $event)
    {
        /** @var WelfareApplication $application */
        $application = $event->getEntity();

        if ($application->getType() !== 'rcel') return;

        $fundType = $this->em->getReference('PersonnelBundle:WelfareFund', 'Royal Commonwealth Ex-services League (RCEL)');

        $fund = new FundReceived();
        $fund->setAmount($application->getAmount());
        $fund->setDate(new \DateTime());
        $fund->setFundType($fundType);

        $this->em->persist($fund);

        $application->getServiceMan()->addReceivedFund($fund);
    }


}
