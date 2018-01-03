<?php

namespace PersonnelBundle\Entity;

use AppBundle\Entity\SortableMasterData;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="serving_types")
 * @ORM\Entity()
 */
class ServingType extends SortableMasterData {}
