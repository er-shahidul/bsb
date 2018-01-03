<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="financial_years")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FinancialYearRepository")
 */
class FinancialYear
{
    /**
     * @ORM\Id
     * @ORM\Column(type="smallint", name="id")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", nullable=true, length=50)
     */
    protected $name;

    public function __construct($year = null)
    {
        $this->id = $year;
    }

    public function __toString()
    {
        return (string)$this->id;
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

    public function getLabel()
    {
        return sprintf('%d - %d', $this->id, ($this->id + 1)%100);
    }

    static public function getFinancialLabel($year, $prefix = false)
    {
        $prefix = $prefix ? 'FY ' : '';
        return sprintf('%s%d - %d', $prefix, $year, ($year + 1)%100);
    }
}
