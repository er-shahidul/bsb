<?php

namespace PersonnelBundle\Entity;

use AppBundle\Entity\SortableMasterData;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="blood_groups")
 * @ORM\Entity()
 */
class BloodGroup extends SortableMasterData{}