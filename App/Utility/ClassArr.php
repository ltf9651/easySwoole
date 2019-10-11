<?php

namespace App\Utility;

/**
 * 做一些反射机制有关的 处理
 */
class ClassArr
{
    public function uploadClassStat()
    {
        return [
            "video" => "\App\Utility\Upload\Video",
        ];
    }

    public function initClass($type, $supportedClass, $params = [], $needInstance = true)
    {
        if (!array_key_exists($type, $supportedClass)) {
            return false;
        }

        $className = $supportedClass[$type];

        try {
            return $needInstance ? (new \ReflectionClass($className))->newInstanceArgs($params) : $className;
        } catch (\ReflectionException $e) {
        }

        return true;
    }

}