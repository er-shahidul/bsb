<?php

namespace MedicalBundle\Entity;

use AppBundle\Traits\BlameableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Medicine
 *
 * @ORM\Table(name="medical_medicines")
 * @ORM\Entity(repositoryClass="MedicalBundle\Repository\MedicineRepository")
 */
class Medicine
{
    use BlameableEntity, TimestampableEntity;
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
     * @ORM\Column(name="name", type="string", length=512, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="accountUnit", type="string", length=50, nullable=true)
     */
    private $accountUnit;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_enabled", type="boolean")
     */
    private $enabled = TRUE;

    /**
     * @var int
     *
     * @ORM\Column(name="sort", type="integer")
     */
    private $sort = 0;


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
     * @return Medicine
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
     * Set accountUnit
     *
     * @param string $accountUnit
     *
     * @return Medicine
     */
    public function setAccountUnit($accountUnit)
    {
        $this->accountUnit = $accountUnit;

        return $this;
    }

    /**
     * Get accountUnit
     *
     * @return string
     */
    public function getAccountUnit()
    {
        return $this->accountUnit;
    }

    /**
     * Set enable
     *
     * @param boolean $enabled
     *
     * @return Medicine
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enable
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return Medicine
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

