<?php

namespace PersonnelBundle\Entity;

use AppBundle\Entity\SortableMasterData;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="retirement_reasons")
 * @ORM\Entity()
 */
class RetirementReason extends SortableMasterData
{
}
