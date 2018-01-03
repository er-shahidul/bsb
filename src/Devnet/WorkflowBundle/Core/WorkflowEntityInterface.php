<?php

namespace Devnet\WorkflowBundle\Core;

interface WorkflowEntityInterface
{
    public function getStepRemarks();
    public function skipInitialQueue();
    public function getStepRemark();
    public function setStepRemark(WorkflowStepRemark $remark);
}