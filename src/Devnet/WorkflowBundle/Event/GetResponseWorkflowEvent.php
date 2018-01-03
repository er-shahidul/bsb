<?php

namespace Devnet\WorkflowBundle\Event;


use Devnet\WorkflowBundle\Core\ResponseBuilderData;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class GetResponseWorkflowEvent extends Event
{
    /** @var BaseWorkflowEntity  */
    private $entity;

    /** @var  Response */
    private $response;

    /** @var  ResponseBuilderData */
    private $responseBuilder;

    public function __construct(BaseWorkflowEntity $entity)
    {
        $this->entity = $entity;
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

    /**
     * @return BaseWorkflowEntity
     */
    public function getEntity()
    {
        return $this->entity;
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

}