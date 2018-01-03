<?php

namespace PersonnelBundle\Entity;

use AppBundle\Entity\SortableMasterData;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="genders")
 * @ORM\Entity()
 */
class Gender extends SortableMasterData{}