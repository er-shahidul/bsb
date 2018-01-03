<?php

namespace AppBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use UserBundle\Entity\User;

/**
 * Blameable Trait, usable with PHP >= 5.4
 *
 * @author Roni Saha <roni@rightbrainsolution.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
trait BlameableEntity
{
    /**
     * @var User $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by")
     */
    private $createdBy;

    /**
     * @var User
     *
     * @Gedmo\Blameable(on="update", field={"stepRemarks"})
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="updated_by")
     */
    protected $updatedBy;

    /**
     * Sets createdBy.
     *
     * @param  User $createdBy
     * @return $this
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Returns createdBy.
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Sets updatedBy.
     *
     * @param  User $updatedBy
     * @return $this
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Returns updatedBy.
     *
     * @return User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
}
