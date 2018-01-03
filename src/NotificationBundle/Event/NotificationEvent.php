<?php

namespace Devnet\WorkflowBundle\Event;


use NotificationBundle\Model\NotifiableRecipientInterface;
use Symfony\Component\EventDispatcher\Event;

class NotificationEvent extends Event
{
    /**
     * @var NotifiableRecipientInterface
     */
    private $recipient;

    public function __construct(NotifiableRecipientInterface $recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return NotifiableRecipientInterface
     */
    public function getRecipient()
    {
        return $this->recipient;
    }
}