<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\FileAttachment;
use AppBundle\Form\Type\MultiAttachmentType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddAttachmentsFieldSubscriber implements EventSubscriberInterface
{
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $form->add('attachments', MultiAttachmentType::class, [
            'entry_data_class' => FileAttachment::class
        ]);
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }
}