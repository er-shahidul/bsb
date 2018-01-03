<?php

namespace WelfareBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="WelfareBundle\Repository\BSCRApplicationRepository")
 */
class BSCRApplication extends WelfareApplication
{
    public function getType()
    {
        return 'bscr';
    }
}
