<?php

namespace harlam\Serialize;

use SuperClosure\SerializerInterface;

class Serializer
{
    public $closureMarker = 'SuperClosure';

    /** @var SerializerInterface */
    protected $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param $object
     * @return string
     */
    public function serialize($object): string
    {
        return serialize($this->serializeClosures($object));
    }

    /**
     * @param string $data
     * @return mixed
     */
    public function unserialize(string $data)
    {
        return $this->restoreClosures(unserialize($data));
    }

    /**
     * @param $object
     * @return mixed
     */
    public function serializeClosures($object)
    {
        array_walk_recursive($object, function (&$value) {
            if ($value instanceof \Closure) {
                $value = $this->serializer->serialize($value);
            } elseif (is_object($value) || is_array($value)) {
                $value = $this->serializeClosures($value);
            }
        });

        return $object;
    }

    /**
     * @param $object
     * @return mixed
     */
    public function restoreClosures($object)
    {
        array_walk_recursive($object, function (&$value) {
            if (is_string($value) && strpos($value, $this->closureMarker)) {
                $value = $this->serializer->unserialize($value);
            } elseif (is_object($value) || is_array($value)) {
                $value = $this->restoreClosures($value);
            }
        });

        return $object;
    }
}