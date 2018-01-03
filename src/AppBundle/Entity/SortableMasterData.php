<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class SortableMasterData
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", name="id")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(name="sort", type="smallint")
     */
    private $sort;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function __toString()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }
}
