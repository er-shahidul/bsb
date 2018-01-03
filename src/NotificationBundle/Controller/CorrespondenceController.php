<?php

namespace NotificationBundle\Controller;

use AppBundle\Controller\BaseController;
use Doctrine\ORM\QueryBuilder;
use NotificationBundle\Datatables\NotificationRecipientDatatable;
use NotificationBundle\Datatables\NotificationSentDatatable;
use NotificationBundle\Entity\Notification;
use NotificationBundle\Entity\NotificationRecipient;
use NotificationBundle\Form\NotificationType;
use NotificationBundle\Manager\NotificationManager;
use NotificationBundle\Mapper\NotificationData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/daily-correspondence")
 */
class CorrespondenceController extends BaseController
{
    /**
     * @Route("/inbox/", name="correspondence_inbox")
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(NotificationRecipientDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("notificationrecipient.user = :user");
            $qb->setParameter('user', $this->getUser());
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('NotificationBundle:Correspondence:inbox.html.twig', array(
            'notification_count' => $this->get(NotificationManager::class)->getNewNotificationCount($this->getUser()),
            'datatable'          => $datatable,
        ));

    }

    /**
     * @Route("/sent/", name="correspondence_sent")
     */
    public function sentListAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(NotificationSentDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("usernotification.createdBy = :user");
            $qb->setParameter('user', $this->getUser());
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('NotificationBundle:Correspondence:sent.html.twig', array(
            'notification_count' => $this->get(NotificationManager::class)->getNewNotificationCount($this->getUser()),
            'datatable'          => $datatable,
        ));

    }

    /**
     * @Route("/show/{id}", name="notification_sent_view", options={"expose"=true})
     * @param Notification $notification
     *
     * @return Response
     */
    public function showAction(Notification $notification)
    {
        if ($notification->getCreatedBy()->getId() != $this->getUser()->getId()) {
            throw $this->createAccessDeniedException();
        }

        $message = $this->renderView(
            '@Notification/Correspondence/_sent_to.html.twig',
            [
                'notification'    => $notification,
                'recipients' => $this->getDoctrine()->getRepository('NotificationBundle:NotificationRecipient')->findBy(['notification' => $notification])
            ]
        );

        return new JsonResponse([
            'message' => $message,
            'link'    => $notification->getLink(),
            'subject' => $notification->getSubject(),
        ]);
    }

    /**
     * @Route("/new", name="new_correspondence")
     * @Template("@Notification/Correspondence/new.html.twig")
     * @param Request $request
     *
     * @return Response|array
     */
    public function newAction(Request $request)
    {
        $notification = new NotificationData();

        $form = $this->createForm(NotificationType::class, $notification, ['user' => $this->getUser()]);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                /** @var NotificationData $data */
                $data = $form->getData();
                $this->get(NotificationManager::class)->sendNotification(
                    $data->getUsers(),
                    $data->getSubject(),
                    $data->getMessage(),
                    NULL, NULL,
                    $this->getUser()
                );

                return $this->redirectToRoute('correspondence_sent');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/view/{id}", name="notification_view", options={"expose"=true})
     * @param NotificationRecipient $recipient
     * @return Response
     */
    public function viewAction(NotificationRecipient $recipient)
    {
        if($recipient->getUser()->getId() != $this->getUser()->getId()) {
            throw $this->createAccessDeniedException();
        }

        if (!$recipient->isSeen()) {
            $recipient->setSeen(true);
            $this->getDoctrine()->getRepository('NotificationBundle:NotificationRecipient')->update($recipient);
        }

        $message = $this->renderView(
            '@Notification/Correspondence/_view.html.twig',
            [
                'notification'    => $recipient->getNotification()
            ]
        );

        return new JsonResponse([
            'message' => $message,
            'link' => $recipient->getNotification()->getLink(),
            'subject' => $recipient->getNotification()->getSubject(),
        ]);
    }
}
