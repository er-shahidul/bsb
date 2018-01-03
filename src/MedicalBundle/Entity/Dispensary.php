<?php

namespace MedicalBundle\Entity;

use AppBundle\Entity\Office;
use AppBundle\Entity\OfficeAwareEntityInterface;
use AppBundle\Traits\BlameableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Dispensary
 *
 * @ORM\Table(name="medical_dispensaries")
 * @ORM\Entity(repositoryClass="MedicalBundle\Repository\DispensaryRepository")
 */
class Dispensary implements OfficeAwareEntityInterface
{
    use BlameableEntity, TimestampableEntity;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Office
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Office")
     */
    private $office;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="enable", type="boolean")
     */
    private $enable = TRUE;

    /**
     * @ORM\OneToMany(targetEntity="MedicalBundle\Entity\Requisition", mappedBy="dispensary")
     */
    protected $requisitions;

    public function __construct()
    {
        $this->requisitions = new ArrayCollection();
    }

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
     * Set office
     *
     * @param Office $office
     *
     * @return Dispensary
     */
    public function setOffice($office)
    {
        $this->office = $office;

        return $this;
    }

    /**
     * Get office
     *
     * @return Office
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Dispensary
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
     * Set enable
     *
     * @param boolean $enable
     *
     * @return Dispensary
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * Get enable
     *
     * @return bool
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @return ArrayCollection
     */
    public function getRequisitions()
    {
        return $this->requisitions;
    }

    /**
     * @param mixed $requisitions
     *
     * @return Dispensary
     */
    public function setRequisitions($requisitions)
    {
        $this->requisitions = $requisitions;

        return $this;
    }
}

