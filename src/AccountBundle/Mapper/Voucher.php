<?php

namespace AccountBundle\Mapper;

class Voucher
{
    protected $amount;
    protected $paymentTo;
    protected $paymentAgainst;
    protected $chequeNumber;
    protected $bankAccount;
    protected $voucherDetails;
    protected $description;

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
     * @return Voucher
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentTo()
    {
        return $this->paymentTo;
    }

    /**
     * @param mixed $paymentTo
     *
     * @return Voucher
     */
    public function setPaymentTo($paymentTo)
    {
        $this->paymentTo = $paymentTo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentAgainst()
    {
        return $this->paymentAgainst;
    }

    /**
     * @param mixed $paymentAgainst
     *
     * @return Voucher
     */
    public function setPaymentAgainst($paymentAgainst)
    {
        $this->paymentAgainst = $paymentAgainst;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChequeNumber()
    {
        return $this->chequeNumber;
    }

    /**
     * @param mixed $chequeNumber
     *
     * @return Voucher
     */
    public function setChequeNumber($chequeNumber)
    {
        $this->chequeNumber = $chequeNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * @param mixed $bankAccount
     *
     * @return Voucher
     */
    public function setBankAccount($bankAccount)
    {
        $this->bankAccount = $bankAccount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVoucherDetails()
    {
        return empty($this->voucherDetails) ? [] : $this->voucherDetails;
    }

    /**
     * @param mixed $voucherDetails
     *
     * @return Voucher
     */
    public function setVoucherDetails($voucherDetails)
    {
        $this->voucherDetails = $voucherDetails;

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
     * @return Voucher
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}