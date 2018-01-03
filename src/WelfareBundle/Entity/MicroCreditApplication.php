<?php

namespace WelfareBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="WelfareBundle\Repository\MicroCreditApplicationRepository")
 */
class MicroCreditApplication extends WelfareApplication
{
    /**
     * @ORM\OneToOne(targetEntity="WelfareBundle\Entity\MicroCreditApplicationDetail", mappedBy="application", cascade={"persist"})
     * @Assert\Valid
     */
    protected $microCreditDetail;

    public function getType()
    {
        return 'micro-credit';
    }

    /**
     * @return mixed
     */
    public function getMicroCreditDetail()
    {
        return $this->microCreditDetail;
    }

    /**
     * @param mixed $microCreditDetail
     * @return $this
     */
    public function setMicroCreditDetail(MicroCreditApplicationDetail $microCreditDetail)
    {
        $this->microCreditDetail = $microCreditDetail;
        $microCreditDetail->setApplication($this);
        return $this;
    }


}
