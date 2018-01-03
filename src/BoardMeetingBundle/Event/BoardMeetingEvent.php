<?php

namespace BoardMeetingBundle\Event;

use BoardMeetingBundle\Entity\BoardMeeting;
use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class BoardMeetingEvent extends Event
{
    const BOARD_MEETING_CREATED = 'board_meeting_created';
    const ATTEND_BOARD_MEETING = 'attend_board_meeting';
    const CLOSE_BOARD_MEETING = 'close_board_meeting';
    const BOARD_MEETING_BEFORE_CREATE = 'board_meeting_before_create';

    /** @var BoardMeeting  */
    private $entity;

    private $error;

    /** @var  Response */
    private $response;

    /** @var  ResponseBuilderData */
    private $responseBuilder;

    public function __construct(BoardMeeting $meeting)
    {
        $this->entity = $meeting;
    }

    /**
     * @return BoardMeeting
     */
    public function getEntity()
    {
        return $this->entity;
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

    /**
     * @return ResponseBuilderData
     */
    public function getResponseBuilder()
    {
        return $this->responseBuilder;
    }

    /**
     * @param ResponseBuilderData $responseBuilder
     */
    public function setResponseBuilder($responseBuilder)
    {
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}