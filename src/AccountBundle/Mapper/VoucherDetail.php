<?php

namespace AccountBundle\Mapper;

class VoucherDetail
{
    protected $fundHead;

    protected $amount;

    /**
     * @return mixed
     */
    public function getFundHead()
    {
        return $this->fundHead;
    }

    /**
     * @param mixed $fundHead
     *
     * @return VoucherDetail
     */
    public function setFundHead($fundHead)
    {
        $this->fundHead = $fundHead;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return (float)$this->amount;
    }

    /**
     * @param mixed $amount
     *
     * @return VoucherDetail
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }
}