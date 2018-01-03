<?php
namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Xiidea\EasyAuditBundle\Resolver\EmbeddedEventResolverInterface;

class BaseEvent extends Event implements EmbeddedEventResolverInterface
{
    protected $candidateProperties = array('name', 'title');

    protected $propertiesFound = array();

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

    public function getEventLogInfo($eventName)
    {
        // TODO: Implement getEventLogInfo() method.
    }

    static public function humanize($value)
    {
        return ucfirst(trim(strtolower(preg_replace(array('/([A-Z])/', '/[_\s]+/'), array('_$1', ' '), $value))));
    }
}