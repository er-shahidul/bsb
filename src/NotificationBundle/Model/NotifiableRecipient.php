<?php

namespace NotificationBundle\Model;


class NotifiableRecipient implements NotifiableRecipientInterface
{
    /**
     * @var
     */
    private $name;
    /**
     * @var null
     */
    private $email;
    /**
     * @var null
     */
    private $cellPhone;

    public function __construct($name, $email = null, $cellPhone = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->cellPhone = $cellPhone;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return null
     */
    public function getCellPhone()
    {
        return $this->cellPhone;
    }

    /**
     * @param null $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param null $cellPhone
     */
    public function setCellPhone($cellPhone)
    {
        $this->cellPhone = $cellPhone;
    }
}