<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BaseEducationalInformation
 *
 * @ORM\MappedSuperclass
 */
abstract class BaseEducationalInformation
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
     * @ORM\Column(name="degree", type="string", length=255)
     */
    private $degree;


    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="institution", type="string", length=255)
     */
    private $institution;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="grade", type="string", length=255)
     */
    private $grade;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="passing_year", type="string", length=255)
     */
    private $passingYear;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="education_type", type="string", length=255, nullable=true)
     */
    private $educationType;

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
     * @return string
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * @param string $degree
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;
    }

    /**
     * @return string
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * @param string $institution
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;
    }

    /**
     * @return string
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param string $grade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
    }

    /**
     * @return string
     */
    public function getPassingYear()
    {
        return $this->passingYear;
    }

    /**
     * @param string $passingYear
     */
    public function setPassingYear($passingYear)
    {
        $this->passingYear = $passingYear;
    }

    /**
     * @return string
     */
    public function getEducationType()
    {
        return $this->educationType;
    }

    /**
     * @param string $educationType
     */
    public function setEducationType($educationType)
    {
        $this->educationType = $educationType;
    }
}

