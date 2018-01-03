<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BaseCareerInformation
 *
 * @ORM\MappedSuperclass
 */
abstract class BaseCareerInformation
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
     * @ORM\Column(name="achievement", type="string", length=255)
     */
    private $achievement;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="achievement_date", type="date")
     */
    private $date;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="remarks", type="string", length=255, nullable=true)
     */
    private $remarks;


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
    public function getAchievement()
    {
        return $this->achievement;
    }

    /**
     * @param string $achievement
     */
    public function setAchievement($achievement)
    {
        $this->achievement = $achievement;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
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

