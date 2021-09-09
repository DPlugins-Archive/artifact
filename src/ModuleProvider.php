<?php

namespace AncientWorks\Artifact;

use AncientWorks\Artifact\Utils\Utils;
use Symfony\Component\Finder\Finder;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 * @copyright 2021 ancientworks
 */
class ModuleProvider
{
    /**
     * The Fully qualified name main class of all artifact's modules
     * @var string[]
     */
    public static $modules = [];

    /**
     * All enabled modules' id as defined on WordPress's option `artifact_module_enabled`
     * @var array
     */
    public static $enabled = [];

    /**
     * Instance of all enabled and loaded modules's class
     * @var array
     */
    public static $container = [];

    /**
     * Instance of all disabled and loaded modules's class
     * @var array
     */
    public static $shadow = [];

    /**
     * All artifact's modules maps
     * @var array
     */
    public static $available = [];

    public static function modules_finder()
    {
        $finder = new Finder();

        $namespace = 'AncientWorks\Artifact\Modules';
        $path = dirname(ARTIFACT_FILE) . DIRECTORY_SEPARATOR . 'Modules';

        $finder
            ->in($path . '/*')
            ->files()
            ->name('*.php')
            ->depth('== 0');

        foreach ($finder as $module) {
            $module = $namespace . str_replace(
                [$path, '/', '.php'],
                ['', '\\', ''],
                $module->getRealPath()
            );

            try {
                if (class_exists($module) && is_subclass_of($module, Module::class)) {
                    array_push(self::$modules, $module);
                }
            } catch (\Throwable $th) {
            }
        }
    }

    public static function loader()
    {
        self::modules_finder();

        self::which_enabled();

        foreach (self::$modules as $module) {
            try {
                if (class_exists($module)) {
                    $m = new $module;

                    if (self::is_enabled($m::$module_id)) {
                        self::$container[$m::$module_id] = $m;
                    } else {
                        self::$shadow[$m::$module_id] = $m;
                    }

                    self::$available[$m::$module_id] = [
                        'enabled' => self::is_enabled($m::$module_id),
                        'fqcn' => $module,
                        'version' => $m::$module_version,
                        'id' => $m::$module_id,
                        'name' => $m::$module_name,
                    ];
                }
            } catch (\Throwable $th) {
            }
        }

        foreach (self::$container as $key => $module) {
            $module->boot();
        }
    }

    public static function which_enabled()
    {
        $modules = Utils::get_option('module_enabled', false);

        if (false === $modules) {
            Utils::update_option('module_enabled', []);
            self::$enabled = [];
        }

        self::$enabled = $modules;
    }

    public static function is_enabled(string $module_id)
    {
        if (defined('ARTIFACT_ENABLE_ALL_MODULES') && ARTIFACT_ENABLE_ALL_MODULES === true) {
            return true;
        }

        if (is_array(self::$enabled) && !empty(self::$enabled)) {
            $enabled = array_key_exists($module_id, self::$enabled);
            return $enabled && $enabled === true;
        }

        return false;
    }

    public static function enable_module(string $module_id)
    {
        self::$enabled[$module_id] = true;
        Utils::update_option('module_enabled', self::$enabled);
    }

    public static function disable_module(string $module_id)
    {
        unset(self::$enabled[$module_id]);
        Utils::update_option('module_enabled', self::$enabled);
    }
}
