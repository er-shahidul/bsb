<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FamilyInformation
 *
 * @ORM\MappedSuperclass
 */
abstract class BaseFamilyInformation
{
    use ServicemanRelationTrait;

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
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\RelationType")
     */
    private $relationType;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="dateOfBirth", type="date")
     */
    private $dateOfBirth;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="occupation", type="string", length=255, nullable=true)
     */
    private $occupation;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_number", type="string", length=255, nullable=true)
     */
    private $mobileNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="nid_or_birth_certificate", type="string", length=255, nullable=true)
     */
    private $nidOrBirthCertificate;

    /**
     * @var string
     *
     * @ORM\Column(name="nok_percentage", type="string", nullable=true)
     */
    private $nokPercentage;

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
     * @return $this
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
     * Set relation
     *
     * @param RelationType $relationType
     *
     * @return $this
     */
    public function setRelationType($relationType)
    {
        $this->relationType = $relationType;

        return $this;
    }

    /**
     * Get relation
     *
     * @return RelationType
     */
    public function getRelationType()
    {
        return $this->relationType;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return $this
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set occupation
     *
     * @param string $occupation
     *
     * @return $this
     */
    public function setOccupation($occupation)
    {
        $this->occupation = $occupation;

        return $this;
    }

    /**
     * Get occupation
     *
     * @return string
     */
    public function getOccupation()
    {
        return $this->occupation;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * @param string $mobileNumber
     */
    public function setMobileNumber($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;
    }

    /**
     * @return string
     */
    public function getNokPercentage()
    {
        return $this->nokPercentage;
    }

    /**
     * @param string $nokPercentage
     */
    public function setNokPercentage($nokPercentage)
    {
        $this->nokPercentage = $nokPercentage;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getNameWithRelation()
    {
        return $this->name.' ('.$this->relationType.')';
    }

    /**
     * @return string
     */
    public function getNidOrBirthCertificate()
    {
        return $this->nidOrBirthCertificate;
    }

    /**
     * @param string $nidOrBirthCertificate
     */
    public function setNidOrBirthCertificate($nidOrBirthCertificate)
    {
        $this->nidOrBirthCertificate = $nidOrBirthCertificate;
    }
}

