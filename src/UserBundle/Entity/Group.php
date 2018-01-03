<?php

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="user_groups")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\GroupRepository")
 * @UniqueEntity(
 *     fields={"name"},
 *     message="This group name is already in use."
 * )
 */
class Group extends BaseGroup
{

    use BlameableEntity, TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     **/
    protected $users;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OfficeType")
     */
    protected $officeType;

    public function __construct($name, $roles = array())
    {
        parent::__construct($name, $roles);
        $this->users = new ArrayCollection();
    }

    /**
     * Set description
     *
     * @param mixed $description
     * @return Group
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return mixed
     */
    public function getOfficeType()
    {
        return $this->officeType;
    }

    /**
     * @param mixed $officeType
     *
     * @return Group
     */
    public function setOfficeType($officeType)
    {
        $this->officeType = $officeType;

        return $this;
    }
}