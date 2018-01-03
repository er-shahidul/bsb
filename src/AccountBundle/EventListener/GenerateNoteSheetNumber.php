<?php
namespace AccountBundle\EventListener;

use AccountBundle\Entity\SanctionEntry;
use Doctrine\ORM\Event\LifecycleEventArgs;

class GenerateNoteSheetNumber
{
    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var SanctionEntry $object */
        $object = $args->getObject();

        if (!$object instanceof SanctionEntry) {
            return;
        }
        $objectManager = $args->getObjectManager();

        $nextNoteSheetId = $objectManager
            ->getRepository('AccountBundle:SanctionEntry')
            ->getNextNoteSheetNumber($object);
        $object->setNoteSheetNumber(sprintf('%s/%s', $nextNoteSheetId, date('y')));
        $object->setNoteSheetGeneratedId($nextNoteSheetId);
    }
}