<?php

namespace BudgetBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * BudgetHead
 *
 * @ORM\Table(name="budget_head")
 * @ORM\Entity(repositoryClass="BudgetBundle\Repository\BudgetHeadRepository")
 * @UniqueEntity(
 *     fields={"code"},
 *     message="This code is already in use."
 * )
 */
class BudgetHead
{
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
     *
     * @ORM\Column(name="code", type="string", length=10)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="titleEn", type="string", length=255)
     */
    private $titleEn;

    /**
     * @var string
     *
     * @ORM\Column(name="titleBn", type="string", length=255)
     */
    private $titleBn;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="smallint")
     */
    private $sort;

    /**
     * @var integer
     *
     * @ORM\Column(name="star", type="smallint", nullable=true)
     */
    private $star;

    /**
     * @ORM\ManyToOne(targetEntity="BudgetHead", inversedBy="child")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="BudgetHead", mappedBy="parent")
     * @ORM\OrderBy({"sort" = "ASC", "id" = "ASC"})
     */
    private $child;

    public function __construct()
    {
        $this->child = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitleBn();
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
     * Set code
     *
     * @param string $code
     *
     * @return BudgetHead
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set titleEn
     *
     * @param string $titleEn
     *
     * @return BudgetHead
     */
    public function setTitleEn($titleEn)
    {
        $this->titleEn = $titleEn;

        return $this;
    }

    /**
     * Get titleEn
     *
     * @return string
     */
    public function getTitleEn()
    {
        return $this->titleEn;
    }

    /**
     * Set titleBn
     *
     * @param string $titleBn
     *
     * @return BudgetHead
     */
    public function setTitleBn($titleBn)
    {
        $this->titleBn = $titleBn;

        return $this;
    }

    /**
     * Get titleBn
     *
     * @return string
     */
    public function getTitleBn()
    {
        return $this->titleBn;
    }

    /**
     * Set parent
     *
     * @param \stdClass $parent
     *
     * @return BudgetHead
     */
    public function setParent($budgetHead)
    {
        $this->parent = $budgetHead;

        return $this;
    }

    /**
     * Get parent
     *
     * @return $this
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return ArrayCollection
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @param mixed $children
     *
     * @return BudgetHead
     */
    public function setChild($budgetHeads)
    {
        $this->child = $budgetHeads;

        return $this;
    }

    public function addChildren($budgetHead)
    {
        if (!$this->child->contains($budgetHead)) {
            $this->child->add($budgetHead);
        }
    }

    public function removeChildren($budgetHead)
    {
        if ($this->child->contains($budgetHead)) {
            $this->child->removeElement($budgetHead);
        }
    }

    public function getLabel()
    {
        return $this->code . ' - ' . $this->titleBn;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     *
     * @return BudgetHead
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStar()
    {
        return $this->star;
    }

    /**
     * @param mixed $star
     *
     * @return BudgetHead
     */
    public function setStar($star)
    {
        $this->star = $star;

        return $this;
    }

}