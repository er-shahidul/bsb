<?php

namespace Devnet\WorkflowBundle\Core;

class WorkflowStepRemark
{
    private $place;
    private $remarks;

    /**
     * WorkflowStepRemark constructor.
     * @param $place
     * @param $remarks
     */
    public function __construct($place, $remarks)
    {
        $this->place = $place;
        $this->remarks = $remarks;
    }

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @return mixed
     */
    public function getRemarks()
    {
        return $this->remarks;
    }
}