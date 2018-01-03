<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FundHeadPayeePayer
 *
 * @ORM\Table(name="account_payee_payer")
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\PayeePayerRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity_type", type="string")
 * @ORM\DiscriminatorMap({"payee" = "Payee", "payer" = "Payer"})
 */
abstract class PayeePayer
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="use_for", type="string", length=255)
     */
    private $useFor;

    /**
     * @var FundHead
     *
     * @ORM\ManyToOne(targetEntity="AccountBundle\Entity\FundType")
     */
    private $fundType;

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
     *
     * @return PayeePayer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getUseFor()
    {
        return $this->useFor;
    }

    /**
     * @param string $useFor
     *
     * @return PayeePayer
     */
    public function setUseFor($useFor)
    {
        $this->useFor = $useFor;

        return $this;
    }

    /**
     * @return FundHead
     */
    public function getFundType()
    {
        return $this->fundType;
    }

    /**
     * @param FundHead $fundType
     *
     * @return PayeePayer
     */
    public function setFundType($fundType)
    {
        $this->fundType = $fundType;

        return $this;
    }
}

