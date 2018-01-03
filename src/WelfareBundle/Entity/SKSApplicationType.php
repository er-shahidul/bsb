<?php

namespace WelfareBundle\Entity;

use AppBundle\Entity\SortableMasterData;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="sks_application_types")
 * @ORM\Entity()
 */
class SKSApplicationType extends SortableMasterData {}