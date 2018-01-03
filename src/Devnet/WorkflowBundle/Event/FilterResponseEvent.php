<?php

namespace Devnet\WorkflowBundle\Event;


use Devnet\WorkflowBundle\Entity\TaskQueue;
use Symfony\Component\EventDispatcher\Event;

class FilterResponseEvent extends Event
{

    private $response;
    /**
     * @var TaskQueue
     */
    private $taskQueue;

    public function __construct(TaskQueue $taskQueue, $defaultResponse = null)
    {
        $this->taskQueue = $taskQueue;
        $this->response = $defaultResponse;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return TaskQueue
     */
    public function getTaskQueue()
    {
        return $this->taskQueue;
    }

    /**
     * @param TaskQueue $taskQueue
     */
    public function setTaskQueue($taskQueue)
    {
        $this->taskQueue = $taskQueue;
    }
}