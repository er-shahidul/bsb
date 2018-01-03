<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BankBranch
 *
 * @ORM\Table(name="bank_branch")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\BankBranchRepository")
 */
class BankBranch
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
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\Bank", inversedBy="branches")
     */
    private $bank;

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
     * @return BankBranch
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
     * Set bank
     *
     * @param \stdClass $bank
     *
     * @return BankBranch
     */
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Get bank
     *
     * @return \stdClass
     */
    public function getBank()
    {
        return $this->bank;
    }

    public function getNameWithBank()
    {
        return $this->bank->getName() . ' - ' . $this->getName();
    }
}

