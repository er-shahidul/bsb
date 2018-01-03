<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EmploymentInformation
 *
 * @ORM\MappedSuperclass
 */
abstract class BaseEmploymentInformation
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
     * @ORM\Column(name="unit", type="string", length=255)
     */
    private $unit;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="form_date", type="date")
     */
    private $fromDate;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="to_date", type="date")
     */
    private $toDate;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="class", type="string", length=255, nullable=true)
     */
    private $class;

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
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    /**
     * @return \DateTime
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * @param \DateTime $fromDate
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;
    }

    /**
     * @return \DateTime
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * @param \DateTime $toDate
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }
}

