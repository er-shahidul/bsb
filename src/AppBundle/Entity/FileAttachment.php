<?php

namespace AppBundle\Entity;

use AppBundle\Traits\BlameableEntity;
use AppBundle\Utility\FileUtil;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * FileAttachment
 *
 * @ORM\Table(name="file_attachments")
 * @ORM\Entity
 */
class FileAttachment
{
    use TimestampableEntity, BlameableEntity;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="module", type="string", length=255)
     */
    private $module;

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="string", length=255)
     */
    private $entity;

    /**
     * @ORM\Column
     */
    private $mimeType;

    /**
     * @ORM\Column
     */
    private $extension;

    /**
     * @var integer
     *
     * @ORM\Column(name="size", type="decimal")
     */
    private $size;

    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
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

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     *
     * @return $this
     */
    public function setFile($file)
    {
        $this->updatedAt = new \DateTimeImmutable('now');
        $this->file = $file;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param mixed $mimeType
     *
     * @return $this
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getFormattedSize()
    {
        return FileUtil::formatSize($this->size);
    }

    public function getIconClass()
    {
        return 'file-' .FileUtil::getIconClassForMime($this->extension);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getWebPath()
    {
        return $this->getUploadDir() . DIRECTORY_SEPARATOR . $this->path;
    }

    public function getAbsolutePath()
    {
        return WEB_PATH . DIRECTORY_SEPARATOR . $this->getWebPath();
    }

    public function getUploadDirectory()
    {
        $fileSystem = new Filesystem();
        $fullPath = WEB_PATH . DIRECTORY_SEPARATOR . $this->getUploadDir();

        if (!$fileSystem->exists($fullPath)) {
            $fileSystem->mkdir($fullPath);
        }

        return $fullPath;
    }


    public function getType()
    {
        return FileUtil::getTypeFromMime($this->mimeType);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    private function getUploadDir()
    {
        $parts = ["uploads", "attachments"];

        if (!empty($this->getModule())) {
            $parts[] = $this->module;
        }

        return implode(DIRECTORY_SEPARATOR, $parts);
    }

    /**
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    public function __toString()
    {
        return $this->getDescription();
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    public function setAttachable($object)
    {
        $reflectionClass = new \ReflectionClass($object);
        $this->module = strtolower($reflectionClass->getShortName());
        $this->entity = $reflectionClass->getName();
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }
}