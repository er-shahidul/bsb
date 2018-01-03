<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Xiidea\EasyAuditBundle\Entity\BaseAuditLog;

/**
 * @ORM\Entity
 * @ORM\Table(name="audit_log")
 */
class AuditLog extends BaseAuditLog
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Type Of Event(Internal Type ID)
     *
     * @var string
     * @ORM\Column(name="type_id", type="string", length=200, nullable=false)
     */
    protected $typeId;

    /**
     * Type Of Event
     *
     * @var string
     * @ORM\Column(name="type_name", type="string", length=200, nullable=true)
     */
    protected $type;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * Time Of Event
     * @var \DateTime
     * @ORM\Column(name="event_time", type="datetime")
     */
    protected $eventTime;

    /**
     * @var string
     * @ORM\Column(name="user_name", type="string", length=255)
     */
    protected $user;

    /**
     * @var string
     * @ORM\Column(name="profile_info", type="string", length=255, nullable=true)
     */
    protected $profileInfo;

    /**
     * @var string
     * @ORM\Column(name="object_class", type="string", length=100, nullable=true)
     */
    protected $objectClass;

    /**
     * @var string
     * @ORM\Column(name="object_type", type="string", length=100, nullable=true)
     */
    protected $objectType;

    /**
     * @var string
     * @ORM\Column(name="object_id", type="bigint", nullable=true)
     */
    protected $objectId;

    /**
     * @var string
     * @ORM\Column(name="change_meta", type="text", nullable=true)
     */
    protected $metaData;

    /**
     * @var string
     * @ORM\Column(name="ip_address", type="string", length=20, nullable=true)
     */
    protected $ip;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getObjectClass()
    {
        return $this->objectClass;
    }

    /**
     * @param string $objectClass
     *
     * @return AuditLog
     */
    public function setObjectClass($objectClass)
    {
        $this->objectClass = $objectClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getObjectType()
    {
        return $this->objectType;
    }

    /**
     * @param string $objectType
     *
     * @return AuditLog
     */
    public function setObjectType($objectType)
    {
        $this->objectType = $objectType;

        return $this;
    }

    /**
     * @return string
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param string $objectId
     *
     * @return AuditLog
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * @return string
     */
    public function getMetaData()
    {
        return $this->metaData;
    }

    /**
     * @param string $metaData
     *
     * @return AuditLog
     */
    public function setMetaData($metaData)
    {
        $this->metaData = $metaData;

        return $this;
    }

    /**
     * @return string
     */
    public function getProfileInfo()
    {
        return $this->profileInfo;
    }

    /**
     * @param string $profileInfo
     */
    public function setProfileInfo($profileInfo)
    {
        $this->profileInfo = $profileInfo;
    }

}