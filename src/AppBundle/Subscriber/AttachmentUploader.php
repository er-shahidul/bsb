<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\AttachmentsTrait;
use AppBundle\Entity\FileAttachment;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachmentUploader implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'preRemove',
        );
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->deleteAttachmentIfRemoved($args);
        $this->upload($args);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->upload($args);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof FileAttachment) {
            return;
        }

        $this->deleteOldFile($entity);
    }

    private function upload(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof FileAttachment) {
            return;
        }

        $uploadedFile = $entity->getFile();

        if ($uploadedFile == NULL) {
            return;
        }

        $path = $this->getFileName($uploadedFile);

        $entity->setName($uploadedFile->getClientOriginalName());
        $entity->setSize($uploadedFile->getClientSize());
        $entity->setMimeType($uploadedFile->getClientMimeType());
        $entity->setExtension(strtolower($uploadedFile->getClientOriginalExtension()));

        $uploadedFile->move($entity->getUploadDirectory(), basename($path));

        $this->deleteOldFile($entity);

        $entity->setPath($path);
        $entity->setFile(NULL);
    }

    private function getFileName(UploadedFile $file)
    {
        return sha1(uniqid(mt_rand(), TRUE)) . '.' . $file->guessExtension();
    }

    private function deleteOldFile(FileAttachment $entity)
    {
        if (empty($entity->getPath())) {
            return;
        }

        $fileSystem = new Filesystem();

        if ($fileSystem->exists($entity->getAbsolutePath())) {
            $fileSystem->remove([$entity->getAbsolutePath()]);
        }
    }

    private function deleteAttachmentIfRemoved(LifecycleEventArgs $args)
    {
        /** @var AttachmentsTrait $entity */
        $entity = $args->getObject();

        if (!AttachmentsTrait::hasAttachmentTrait(get_class($entity))) {
            return;
        }

        $arrayCollection = $entity->getAttachments();

        if ($arrayCollection instanceof PersistentCollection) {
            $deleted = $arrayCollection->getDeleteDiff();
            if (count($deleted) < 1) {
                return;
            }
            $attachmentRepo = $args->getObjectManager();
            foreach ($deleted as $attachment) {
                $attachmentRepo->remove($attachment);
            }
        }
    }
}