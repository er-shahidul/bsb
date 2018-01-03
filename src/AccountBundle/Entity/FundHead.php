<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FundHead
 *
 * @ORM\Table(name="fund_head")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\FundHeadRepository")
 */
class FundHead
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="sort", type="smallint")
     */
    private $sort;

    /**
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\FundType")
     */
    private $fundType;


    /**
     * OfficeType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OfficeType")
     */
    private $officeType;

    /**
     * @ORM\Column(name="is_enabled", type="boolean")
     */
    private $enabled = TRUE;


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
     * @return FundHead
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
     * Set sort
     *
     * @param integer $sort
     *
     * @return FundHead
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

    /**
     * @return mixed
     */
    public function getFundType()
    {
        return $this->fundType;
    }

    /**
     * @param mixed $fundType
     *
     * @return FundHead
     */
    public function setFundType($fundType)
    {
        $this->fundType = $fundType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOfficeType()
    {
        return $this->officeType;
    }

    /**
     * @param mixed $officeType
     */
    public function setOfficeType($officeType)
    {
        $this->officeType = $officeType;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }
}

