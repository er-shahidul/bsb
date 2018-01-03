<?php

namespace AppBundle\Subscriber;

use Xiidea\EasyAuditBundle\Subscriber\EasyAuditEventSubscriberInterface;

class AuditLogEventSubscriber implements EasyAuditEventSubscriberInterface
{
    public function getSubscribedEvents()
    {
        return array(
            "user.event_resolver" => array(
                "security.interactive_login",
                "security.authentication.failure",
                "fos_user.change_password.edit.completed",
                "fos_user.security.implicit_login"
            ),
            "entity.created",
            "entity.updated",
            "entity.deleted",
            "budget.created",
            "budget.updated",
        );
    }
}