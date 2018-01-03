<?php

namespace AccountBundle\Mapper;

class Sanction
{
    protected $fundType;
    protected $description;
    protected $voucherDate;
    protected $workflowLabel;
    protected $vouchers;

    public function __construct($workflowLabel)
    {
        $this->workflowLabel = $workflowLabel;
    }

    /**
     * @return mixed
     */
    public function getFundType()
    {
        return $this->fundType;
    }

    /**
     * @param mixed $fundType
     *
     * @return Sanction
     */
    public function setFundType($fundType)
    {
        $this->fundType = $fundType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return Sanction
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVoucherDate()
    {
        return $this->voucherDate;
    }

    /**
     * @param mixed $voucherDate
     *
     * @return Sanction
     */
    public function setVoucherDate($voucherDate)
    {
        $this->voucherDate = $voucherDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVouchers()
    {
        return $this->vouchers;
    }

    /**
     * @param mixed $vouchers
     *
     * @return Sanction
     */
    public function setVouchers($vouchers)
    {
        $this->vouchers = $vouchers;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWorkflowLabel()
    {
        return $this->workflowLabel;
    }

    /**
     * @param mixed $workflowLabel
     *
     * @return Sanction
     */
    public function setWorkflowLabel($workflowLabel)
    {
        $this->workflowLabel = $workflowLabel;

        return $this;
    }
}
