<?php

namespace Unvurn\Http;

trait HasCachedCreator
{
    public static function create(string $key): static
    {
        static $instances = [];

        if (array_key_exists($key, $instances)) {
            return $instances[$key];
        }

        $instance = new static($key);
        $instances[$key] = $instance;
        return $instance;
    }
}
