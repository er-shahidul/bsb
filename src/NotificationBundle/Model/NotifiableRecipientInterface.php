<?php

namespace NotificationBundle\Model;

interface NotifiableRecipientInterface
{
    public function getCellPhone();
    public function getEmail();
    public function getName();
}