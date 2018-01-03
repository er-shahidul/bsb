<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FundType
 *
 * @ORM\Table(name="fund_type")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\FundTypeRepository")
 */
class FundType
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="basbFund", type="boolean")
     */
    private $basbFund;

    /**
     * @var int
     *
     * @ORM\Column(name="sort", type="smallint")
     */
    private $sort;

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return FundType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set basbFund
     *
     * @param boolean $basbFund
     *
     * @return FundType
     */
    public function setBasbFund($basbFund)
    {
        $this->basbFund = $basbFund;

        return $this;
    }

    /**
     * Get basbFund
     *
     * @return bool
     */
    public function getBasbFund()
    {
        return $this->basbFund;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return FundType
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }
}

