<?php

namespace PersonnelBundle\Entity;


trait ServicemanRelationTrait
{
    protected $serviceman;

    /**
     * @return ExServiceman|ServingMilitary|ServingCivilian
     */
    public function getServiceman()
    {
        return $this->serviceman;
    }

    /**
     * @param ExServiceman|ServingMilitary|ServingCivilian|Personnel $serviceman
     * @return $this
     */
    public function setServiceman($serviceman)
    {
        $this->serviceman = $serviceman;

        return $this;
    }
}