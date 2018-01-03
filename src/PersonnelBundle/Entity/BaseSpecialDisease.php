<?php

namespace PersonnelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BaseSpecialDisease
 *
 * @ORM\MappedSuperclass
 */
abstract class BaseSpecialDisease
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
    private $name;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="affected_date", type="date")
     */
    private $affectedDate;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="proving_by", type="string", length=255, nullable=true)
     */
    private $provingBy;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="treatment", type="string", length=255, nullable=true)
     */
    private $treatment;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="remarks", type="string", length=255, nullable=true)
     */
    private $remark;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime
     */
    public function getAffectedDate()
    {
        return $this->affectedDate;
    }

    /**
     * @param \DateTime $affectedDate
     */
    public function setAffectedDate($affectedDate)
    {
        $this->affectedDate = $affectedDate;
    }

    /**
     * @return string
     */
    public function getProvingBy()
    {
        return $this->provingBy;
    }

    /**
     * @param string $provingBy
     */
    public function setProvingBy($provingBy)
    {
        $this->provingBy = $provingBy;
    }

    /**
     * @return string
     */
    public function getTreatment()
    {
        return $this->treatment;
    }

    /**
     * @param string $treatment
     */
    public function setTreatment($treatment)
    {
        $this->treatment = $treatment;
    }

    /**
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param string $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
    }
}

