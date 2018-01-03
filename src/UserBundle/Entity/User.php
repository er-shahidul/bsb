<?php

namespace UserBundle\Entity;

use AppBundle\Entity\Office;
use AppBundle\Entity\OfficeAwareEntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use MedicalBundle\Entity\Dispensary;
use PersonnelBundle\Entity\Personnel;
use PersonnelBundle\Entity\ServingPersonnel;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Xiidea\EasyAuditBundle\Annotation\ORMSubscribedEvents;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 * @UniqueEntity(
 *     fields={"email"},
 *     message="This email is already in use."
 * )
 * @UniqueEntity(
 *     fields={"username"},
 *     message="This username is already in use."
 * )
 * @ORMSubscribedEvents()
 */
class User extends BaseUser implements OfficeAwareEntityInterface
{
    use BlameableEntity, TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * ServingPersonnel
     * @ORM\ManyToOne(targetEntity="PersonnelBundle\Entity\ServingPersonnel")
     */
    protected $profile;

    /**
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     * @ORM\JoinTable(name="join_users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Office", inversedBy="users")
     * @ORM\JoinColumn(name="office_id", referencedColumnName="id")
     */
    protected $office;

    /**
     * @var Dispensary
     *
     * @ORM\ManyToOne(targetEntity="MedicalBundle\Entity\Dispensary")
     * @ORM\JoinColumn(name="dispensary_id", referencedColumnName="id")
     */
    protected $dispensary;

    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
    }

    /**
     * @return ServingPersonnel
     */
    public function getProfile()
    {
        return $this->profile;
    }

    public function getProfileId()
    {
        if (!$this->getProfile()) {
            return NULL;
        }

        return $this->getProfile()->getId();
    }

    /**
     * @param ServingPersonnel $profile
     * @return $this
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;

        return $this;
    }

    public function isSuperAdmin()
    {
        $groups = $this->getGroups();
        /** @var Group $group */
        foreach ($groups as $group) {
            if ($group->hasRole('ROLE_SUPER_ADMIN')) {
                return true;
            }
        }

        return parent::isSuperAdmin();
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param mixed $groups
     *
     * @return User
     */
    public function setGroups($groups)
    {
        $this->groups = is_array($groups) ? $groups : array($groups);

        return $this;
    }

    /**
     * @return Office
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * @param Office $office
     *
     * @return User
     */
    public function setOffice(Office $office = null)
    {
        $this->office = $office;

        return $this;
    }

    public function isOfficeAdmin()
    {
        $officeAdminRole = 'ROLE_OFFICE_ADMIN';
        /** @var Group $group */
        foreach ($this->getGroups() as $group) {
            if ($group->hasRole($officeAdminRole)) {
                return true;
            }
        }

        return $this->hasRole($officeAdminRole);
    }

    public function getNameAndDesig()
    {
        if (!$this->getProfile()) {
            return $this->username;
        }

        return sprintf('%s %s %s', $this->getName(), PHP_EOL, $this->getDesignation());
    }

    public function getNameAndDesignation($template)
    {
        if (!$this->getProfile()) {
            return $this->username;
        }

        return str_replace(['__NAME__', '__DESIGNATION__'], [$this->getName(), $this->getDesignation()], $template);
    }

    public function getName()
    {
        if ($this->getProfile() === NULL || empty($this->getProfile()->getName())) {
            return $this->username;
        }

        return  $this->getProfile()->getName();
    }

    public function getPhoto()
    {
        if (!$this->getProfile()) {
            return Personnel::DEFAULT_AVATAR;
        }

        return $this->getProfile()->getPhotoPath();
    }

    public function getDesignation()
    {
        if ($this->getProfile() === NULL) {
            return '';
        }

        return $this->getProfile()->getDesignation();
    }

    public function getMobileNumber()
    {
        if ($this->getProfile() === NULL) {
            return '';
        }

        return $this->getProfile()->getMobileNumber();
    }

    public function hasInvalidOffice()
    {
        if($this->getOffice() === NULL || $this->getProfile() === NULL || $this->getProfile()->getOffice() === NULL) return FALSE;

        return $this->getProfile()->getOffice()->getId() != $this->getOffice()->getId();
    }

    /**
     * @return mixed
     */
    public function getDispensary()
    {
        return $this->dispensary;
    }

    /**
     * @param mixed $dispensary
     */
    public function setDispensary($dispensary)
    {
        $this->dispensary = $dispensary;
    }
}