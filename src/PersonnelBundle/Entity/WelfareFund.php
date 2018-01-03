<?php

namespace PersonnelBundle\Entity;

use AppBundle\Entity\SortableMasterData;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="welfare_funds")
 * @ORM\Entity()
 */
class WelfareFund extends SortableMasterData{}