<?php

namespace Devnet\WorkflowBundle\Core;

class ResponseBuilderData
{
    private $view;
    private $data;

    public function __construct($template, $data = array())
    {
        $this->view = $template;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}