<?php

namespace AncientWorks\Artifact;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 * @copyright 2021 ancientworks
 */
class ModuleProvider
{
    public static $modules = [
        \AncientWorks\Artifact\Modules\OxygenUnloader\Unloader::class,
        \AncientWorks\Artifact\Modules\Sandbox\Sandbox::class,
    ];

    public static $enabled = [];

    public static $container = [];

    public static function loader()
    {
        self::which_enabled();

        foreach (self::$modules as $module) {
            if (class_exists($module)) {
                $m = new $module;
                self::$container[$m::$module_id] = $m;
            }
        }
    }

    public static function which_enabled()
    {
        $modules = get_option('artifact_module_enabled', false);

        if (false === $modules) {
            update_option('artifact_module_enabled', []);
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
}
