<?php

namespace Devnet\PolicyManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Policy
 *
 * @ORM\Table(name="policies")
 * @ORM\Entity(repositoryClass="Devnet\PolicyManagerBundle\Repository\PolicyRepository")
 */
class Policy
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="string", length=255)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @var bool
     *
     * @ORM\Column(name="locked", type="boolean")
     */
    private $locked = false;

    /**
     * Policy constructor.
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
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
     * Set value
     *
     * @param string $value
     *
     * @return Policy
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     *
     * @return Policy
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return $this->locked;
    }
}

