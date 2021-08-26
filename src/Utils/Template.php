<?php

namespace AncientWorks\Artifact\Utils;

use Exception;
use League\Plates\Engine;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 * @copyright 2021 ancientworks
 */
class Template
{
    private static $instances = [];

    public static $templates;

    protected function __construct()
    {
        self::$templates = new Engine(dirname(ARTIFACT_FILE) . '/templates');
    }

    public static function getInstance(): Template
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    public static function __callStatic(string $method, array $args)
    {
        return self::getInstance()::$templates->{$method}(...$args);
    }

    public function __get(string $name)
    {
        return self::getInstance()::$templates->{$name};
    }

    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    protected function __clone()
    {
    }

    public static function template(): Engine
    {
        return self::getInstance()::$templates;
    }
}
