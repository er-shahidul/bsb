<?php

namespace WelfareBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="WelfareBundle\Repository\RCELApplicationRepository")
 */
class RCELApplication extends WelfareApplication
{
    public function getType()
    {
        return 'rcel';
    }
}
