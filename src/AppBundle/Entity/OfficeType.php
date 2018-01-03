<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="office_types")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OfficeTypeRepository")
 */
class OfficeType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Office", mappedBy="officeType")
     */
    private $offices;

    public function __construct()
    {
        $this->offices = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $offices
     *
     * @return $this
     */
    public function setOffices($offices)
    {
        $this->offices = $offices;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getOffices()
    {
        return $this->offices;
    }

    /**
     *
     */
    public function addOffice($office)
    {
        if (!$this->offices->contains($office)) {
            $this->offices->add($office);
        }
    }

    public function removeOffice($office)
    {
        if ($this->offices->contains($office)) {
            $this->offices->remove($office);
        }
    }

    public function __toString()
    {
        return $this->getName();
    }
}
