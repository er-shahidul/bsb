<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BaseTrainingInformation
 *
 * @ORM\MappedSuperclass
 */
abstract class BaseTrainingInformation
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
     * @ORM\Column(name="course", type="string", length=255)
     */
    private $course;


    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="institute", type="string", length=255)
     */
    private $institute;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="duration", type="string", length=255)
     */
    private $duration;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="remarks", type="string", length=255)
     */
    private $remarks;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param string $course
     */
    public function setCourse($course)
    {
        $this->course = $course;
    }

    /**
     * @return string
     */
    public function getInstitute()
    {
        return $this->institute;
    }

    /**
     * @param string $institute
     */
    public function setInstitute($institute)
    {
        $this->institute = $institute;
    }

    /**
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * @param string $remarks
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
    }
}

