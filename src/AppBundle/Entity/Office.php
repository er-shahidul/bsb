<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Table(name="offices")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OfficeRepository")
 */
class Office
{
    use BlameableEntity, TimestampableEntity;
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
     * @var string
     * @ORM\Column(name="address", type="string", length=200, nullable=true)
     */
    protected $address;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=200, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     * @ORM\Column(name="mobile", type="string", length=200, nullable=true)
     */
    protected $mobile;

    /**
     * @var string
     * @ORM\Column(name="fax", type="string", length=16, nullable=true)
     */
    protected $fax;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=200, nullable=true)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(name="geoCode", type="string", length=10, nullable=true)
     */
    protected $geoCode;

    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\User", mappedBy="office")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OfficeType", inversedBy="offices")
     */
    private $officeType;

    public function __toString()
    {
        return $this->getName();
    }

    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * @return OfficeType
     */
    public function getOfficeType()
    {
        return $this->officeType;
    }

    /**
     * @param OfficeType $officeType
     *
     * @return Office
     */
    public function setOfficeType($officeType)
    {
        $this->officeType = $officeType;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     *
     * @return $this
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    public function isBasb()
    {
        return $this->officeType->getName() === 'HQ';
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param mixed $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getGeoCode()
    {
        return $this->geoCode;
    }

    /**
     * @param mixed $geoCode
     */
    public function setGeoCode($geoCode)
    {
        $this->geoCode = $geoCode;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }
}
