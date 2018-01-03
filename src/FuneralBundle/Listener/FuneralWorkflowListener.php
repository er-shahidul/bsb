<?php

namespace FuneralBundle\Listener;


use Doctrine\ORM\EntityManagerInterface;
use FuneralBundle\Entity\Funeral;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use NotificationBundle\Manager\NotificationManager;
use PersonnelBundle\Entity\ExServiceman;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FuneralWorkflowListener implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;
    /**
     * @var NotificationManager
     */
    private $notificationManager;

    public function __construct(EntityManagerInterface $entityManager, NotificationManager $notificationManager)
    {
        $this->em = $entityManager;
        $this->notificationManager = $notificationManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.view.event' => array(
                array('renderView', 10),
            ),
            'workflow.funeral.entered.approved' => array(
                array('funeralApproved', 10),
            ),
        );
    }

    public function funeralApproved($event)
    {
        /** @var Funeral $funeral  */
        $funeral = $event->getSubject();

        if (!$funeral instanceof Funeral) {
            return;
        }

        $exServiceman = $funeral->getExServiceman();
        $exServiceman->setDeceased(true);
        $exServiceman->setDeceasedReason($funeral->getDeceasedReason());
        $exServiceman->setDeceasedPlace($funeral->getDeceasedPlace());
        $exServiceman->setDeceasedDate($funeral->getDeceasedDate());

        $this->em->flush();

        $this->sendNotificationToOffices($exServiceman);
    }

    public function renderView(GetResponseWorkflowEvent $event)
    {
        /** @var Funeral $funeral */
        $funeral = $event->getEntity();
        if(!$funeral instanceof Funeral) {
            return;
        }
        $builder = new ResponseBuilderData(
            '@Funeral/Funeral/show.html.twig',
            [
                'funeral' => $funeral,
                'entityClass' => get_class($funeral)
            ]
        );
        $event->setResponseBuilder($builder);
    }

    private function sendNotificationToOffices(ExServiceMan $exServiceman)
    {
        $offices = $this->em->getRepository('AppBundle:Office')->findAll();
        $this->notificationManager->sendNotification($offices,
            'Deceased Ex-Serviceman',
            sprintf('%s %s of %s has died at %s. Please pray for his departed soul.',
                $exServiceman->getRank(),
                $exServiceman->getName(),
                $exServiceman->getOffice(),
                $exServiceman->getDeceasedDate()->format('Y-m-d')
            )
        );
    }
}