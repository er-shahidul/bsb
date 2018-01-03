<?php

namespace PersonnelBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="districts")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\DistrictRepository")
 */
class District
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
     * @ORM\OneToMany(targetEntity="PersonnelBundle\Entity\Upazila", mappedBy="district")
     */
    protected $upazilas;

    public function __toString()
    {
        return $this->getName();
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
     * @return ArrayCollection
     */
    public function getUpazilas()
    {
        return $this->upazilas;
    }

    /**
     * @param mixed $upazilas
     *
     * @return District
     */
    public function setUpazilas($upazilas)
    {
        $this->upazilas = $upazilas;

        return $this;
    }
}
