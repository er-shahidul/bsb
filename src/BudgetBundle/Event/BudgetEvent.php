<?php
namespace BudgetBundle\Event;

use AppBundle\Event\BaseEvent;
use BudgetBundle\Entity\Budget;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Xiidea\EasyAuditBundle\Resolver\EmbeddedEventResolverInterface;

class BudgetEvent extends Event implements EmbeddedEventResolverInterface
{
    /**
     * @var
     */
    protected $entity;
    /**
     * @var
     */
    protected $eventName;

    protected $changesValues = array();

    /** @var  Serializer */
    protected $serializer;

    public function __construct($entity, $values)
    {
        $this->entity = $entity;
        $this->changesValues = $values;

        $encoder = array(new XmlEncoder(), new JsonEncoder());
        $normalize = array(new GetSetMethodNormalizer());

        $this->serializer = new Serializer($normalize, $encoder);
    }

    /**
     * @param $eventName
     *
     * @return array
     */
    public function getEventLogInfo($eventName)
    {
        if (null === $this->changesValues) {
            return null;
        }

        $this->eventName = $eventName;
        $reflectionClass = $this->getReflectionClassFromObject($this->entity);

        $typeName = $this->getTypeName($reflectionClass);
        $eventType = $this->getEventType($typeName);
        $eventDescription = $this->getDescriptionString($reflectionClass, $typeName);

        return array(
            'description' => $eventDescription,
            'type' => $eventType,
        );

    }

    /**
     * @param string $typeName
     * @return string
     */
    protected function getEventType($typeName)
    {
        return $typeName . " " . $this->getEventShortName();
    }

    protected function getTypeName(\ReflectionClass $reflectionClass)
    {
        return $reflectionClass->getShortName();
    }

    protected function getDescriptionString(\ReflectionClass $reflectionClass, $typeName)
    {
        $entity = $this->getBudgetEntity();
        $year = $entity->getFinancialYear();

        switch ($this->eventName){
            case 'updated':
                $descriptionTemplate = "Budget Update of %s. old values = %s, new values = %s"; break;
            case 'created':
            default:
                $descriptionTemplate = "Budget Created of %s"; break;
        }

        return sprintf($descriptionTemplate,
            $year,
            $entity->getFinancialYear()->getLabel(),
            $this->jsonToCommaSeparatedKeyValue($this->getJsonSerialized($this->changesValues))
        );
    }


    /**
     * @return Budget
     */
    public function getBudgetEntity()
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    protected function getEventShortName()
    {
        return substr(strrchr($this->eventName, '.'), 1);
    }

    protected function getReflectionClassFromObject($object)
    {
        return new \ReflectionClass(get_class($object));
    }

    protected function getJsonSerialized($entity, $encoded = true)
    {
        return json_encode($entity);
    }

    protected function jsonToCommaSeparatedKeyValue($json)
    {
        $output = array();
        $data = json_decode($json);
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $output[] = $key . ": " . $value;
            }
        }

        return implode(", ", $output);
    }

    public function prepareDiffs($delta) {
        $old = $this->getJsonSerialized($this->entity, false);
        $new = $this->getJsonSerialized($this->changesValues, false);

        $output = array();
        foreach ($delta as $field => $value) {
            $field = BaseEvent::humanize($field);
            $output['old'][$field] = $old[$field];
            $output['new'][$field] = $new[$field];
        }

        return $output;
    }
}