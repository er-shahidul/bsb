<?php

namespace BoardMeetingBundle\Core;


use BoardMeetingBundle\Entity\BoardMeeting;

trait BoardMeetingRelationTrait
{
    /**
     * @var BoardMeeting
     *
     * @ORM\ManyToOne(targetEntity="BoardMeetingBundle\Entity\BoardMeeting")
     */
    protected $meeting;

    /**
     * @return BoardMeeting
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * @param BoardMeeting $meeting
     */
    public function setMeeting($meeting)
    {
        $this->meeting = $meeting;
    }
}