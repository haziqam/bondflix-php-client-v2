<?php

namespace Utils\ArrayMapper;

class ArrayMapper
{
    public static function mapObjectsToArray($objects)
    {
        return array_map(function ($object) {
            return $object->toArray();
        }, $objects);
    }
}