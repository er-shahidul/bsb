<?php

namespace AccountBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Bank
 *
 * @ORM\Table(name="bank")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\BankRepository")
 */
class Bank
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AccountBundle\Entity\BankBranch", mappedBy="bank")
     */
    private $branches;

    public function __construct()
    {
        $this->branches = new ArrayCollection();
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
     * @return Bank
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
     * @return ArrayCollection
     */
    public function getBranches()
    {
        return $this->branches;
    }

    /**
     * @param ArrayCollection $branches
     *
     * @return Bank
     */
    public function setBranches($branches)
    {
        $this->branches = $branches;

        return $this;
    }
}

