<?php

namespace BoardMeetingBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class LoginRequestListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'kernel.response' => array(
                array('onKernelResponse', 10)
            )
        );
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        if ($request->get('_route') !== 'board_meeting_authenticate') {
            return;
        }

        $_token = $request->query->get('_token');

        $response = $event->getResponse();

        if ($response->getStatusCode() < 400) {
            $tokenCookie = new Cookie('_BOARD_MEETING_TOKEN', $_token, 0, '/', NULL, FALSE, TRUE);
            $response->headers->setCookie($tokenCookie);
            $response->send();
        } else {
            $response->headers->clearCookie('_BOARD_MEETING_TOKEN');
        }
    }
}