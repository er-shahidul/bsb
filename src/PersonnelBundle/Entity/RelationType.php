<?php

namespace PersonnelBundle\Entity;

use AppBundle\Entity\SortableMasterData;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="relation_types")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\RelationTypeRepository")
 */
class RelationType extends SortableMasterData{}