<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="upazilas")
 * @ORM\Entity(repositoryClass="PersonnelBundle\Repository\UpazilaRepository")
 */
class Upazila
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
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\District", inversedBy="upazilas")
     */
    protected $district;

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
     * @return District
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * @param District $district
     *
     * @return Upazila
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
