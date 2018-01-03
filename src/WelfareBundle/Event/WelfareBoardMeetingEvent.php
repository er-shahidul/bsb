<?php

namespace WelfareBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class WelfareBoardMeetingEvent extends Event
{
    private $applications;

    private $error;

    public function __construct(Array $applications)
    {
        $this->applications = $applications;
    }

    /**
     * @return array
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }
}