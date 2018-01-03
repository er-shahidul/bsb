<?php

namespace PersonnelBundle\Entity;

use AppBundle\Entity\SortableMasterData;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="religions")
 * @ORM\Entity()
 */
class Religion extends SortableMasterData{}