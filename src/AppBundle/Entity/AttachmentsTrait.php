<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

trait AttachmentsTrait
{
    /**
     * ArrayCollection<FileAttachment>
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\FileAttachment", cascade={"persist"})
     */
    protected $attachments;

    /**
     * @return ArrayCollection
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param FileAttachment $attachment
     */
    public function addAttachment(FileAttachment $attachment)
    {
        if (!$this->getAttachments()->contains($attachment)) {
            $this->getAttachments()->add($attachment);
            $attachment->setAttachable($this);
        }
    }

    /**
     * @param FileAttachment $attachment
     */
    public function removeAttachment(FileAttachment $attachment)
    {
        if ($this->getAttachments()->contains($attachment)) {
            $this->getAttachments()->removeElement($attachment);
        }
    }

    public static function hasAttachmentTrait($class)
    {
        do {
            $traits = class_uses($class);
            if (isset($traits[self::class])) {
                return TRUE;
            }
        } while ($class = get_parent_class($class));

        return FALSE;
    }
}