<?php

namespace WelfareBundle\Entity;

use AppBundle\Entity\AttachmentsTrait;
use BoardMeetingBundle\Core\BoardMeetingRelationTrait;
use BoardMeetingBundle\Entity\BoardMeeting;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use PersonnelBundle\Entity\ExFamilyInformation;
use PersonnelBundle\Entity\ExServiceman;

/**
 * BoardRecommendation
 *
 * @ORM\Table(name="board_recommendations")
 * @ORM\Entity()
 */
class BoardRecommendation extends BaseWorkflowEntity
{
    /**
     * @var BoardMeeting
     *
     * @ORM\OneToOne(targetEntity="BoardMeetingBundle\Entity\BoardMeeting")
     */
    private $meeting;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    function __construct(BoardMeeting $meeting = null)
    {
        $this->meeting = $meeting;
    }

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

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function skipInitialQueue()
    {
        return TRUE;
    }

}

