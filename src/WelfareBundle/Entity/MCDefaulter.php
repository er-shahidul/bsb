<?php

namespace WelfareBundle\Entity;

use AppBundle\Entity\SortableMasterData;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="micro_credit_defaulters")
 * @ORM\Entity()
 */
class MCDefaulter {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="WelfareBundle\Entity\MCDefaulterRegister", inversedBy="defaulters")
     * @ORM\JoinColumn(name="defaulter_register_id")
     */
    private $defaulterRegister;

    /**
     * @ORM\ManyToOne(targetEntity="WelfareBundle\Entity\MicroCreditApplication")
     * @ORM\JoinColumn(name="application_id")
     */
    private $application;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDefaulterRegister()
    {
        return $this->defaulterRegister;
    }

    /**
     * @param mixed $defaulterRegister
     */
    public function setDefaulterRegister($defaulterRegister)
    {
        $this->defaulterRegister = $defaulterRegister;
    }

    /**
     * @return mixed
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param mixed $application
     */
    public function setApplication($application)
    {
        $this->application = $application;
    }


}