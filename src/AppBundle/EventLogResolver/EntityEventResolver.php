<?php
/*
 * This file is part of the Docudex project.
 *
 * (c) Devnet Limited <http://www.devnetlimited.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\EventLogResolver;

use Symfony\Component\EventDispatcher\Event;
use UserBundle\Entity\User;
use Xiidea\EasyAuditBundle\Events\DoctrineEntityEvent;
use Xiidea\EasyAuditBundle\Resolver\EntityEventResolver as BaseResolver;

class EntityEventResolver extends BaseResolver
{
    protected $candidateProperties = array('id', 'name', 'title');

    /**
     * @param Event|DoctrineEntityEvent $event
     * @param $eventName
     *
     * @return array
     */
    public function getEventLogInfo(Event $event, $eventName)
    {
        if (!$event instanceof DoctrineEntityEvent) {
            return null;
        }

        $this->initialize($event, $eventName);

        $changesMetaData = $this->getChangeSets($this->entity);

        // Ignore user last login data update event
        if($this->entity instanceof User && isset($changesMetaData['lastLogin'])) {
            return NULL;
        }

        if ($this->isUpdateEvent() && $changesMetaData == NULL) {
            return NULL;
        }

        $reflectionClass = $this->getReflectionClassFromObject($this->entity);

        $typeName = $this->getTypeName($reflectionClass, $this->entity);
        $eventType = $this->getEventType($typeName);
        $eventDescription = $this->getDescriptionString($reflectionClass, $typeName);


        return array(
            'description' => $eventDescription,
            'metaData' => $this->getAsSerializedString($changesMetaData),
            'type'        => $eventType,
            'objectId'    => $this->getProperty('id'),
            'objectClass' => $reflectionClass->getName(),
            'objectType'  => $typeName
        );

    }

    /**
     * @param DoctrineEntityEvent $event
     * @param string $eventName
     */
    private function initialize(DoctrineEntityEvent $event, $eventName)
    {
        $this->eventShortName = null;
        $this->propertiesFound = array();
        $this->eventName = $eventName;
        $this->event = $event;
        $this->entity = $event->getLifecycleEventArgs()->getEntity();
    }

    protected function getTypeName(\ReflectionClass $reflectionClass, $entity)
    {
        return $reflectionClass->getShortName();
    }

    protected function getDescriptionString(\ReflectionClass $reflectionClass, $typeName)
    {
        $property = $this->getBestCandidatePropertyForIdentify($reflectionClass);

        $descriptionTemplate = '%s has been %s ';

        if ($property) {
            $descriptionTemplate .= sprintf(' with %s = "%s" ', $property, $this->getProperty($property));
        }

        return sprintf($descriptionTemplate,
            $typeName,
            $this->getEventShortName());
    }

    /**
     * @param $changesMetaData
     *
     * @return null|string
     */
    protected function getAsSerializedString($changesMetaData)
    {
        if (empty($changesMetaData)) {
            return NULL;
        } elseif (is_string($changesMetaData)) {
            return $changesMetaData;
        }

        return serialize($changesMetaData);
    }
}