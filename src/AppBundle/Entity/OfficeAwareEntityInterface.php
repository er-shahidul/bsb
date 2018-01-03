<?php

namespace AppBundle\Entity;

interface OfficeAwareEntityInterface
{
    /** @return Office */
    public function getOffice();
}
