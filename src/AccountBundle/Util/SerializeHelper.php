<?php

namespace AccountBundle\Util;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializeHelper
{
    static function serialize($entity, $format = 'json')
    {
        return SerializeHelper::getSerializer()->serialize($entity, $format);
    }

    static function deserialize($data, $entity, $format = 'json')
    {
        return SerializeHelper::getSerializer()->deserialize($data, $entity, $format);
    }

    static function getSerializer()
    {
        $encoders = array(new JsonEncoder());
        $normalizer = new ObjectNormalizer();

        $normalizers = array($normalizer, new DateTimeNormalizer('Y-m-d'));

        return new Serializer($normalizers, $encoders);
    }
}