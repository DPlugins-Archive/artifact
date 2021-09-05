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
    /**
     * The Fully qualified name main class of all artifact's modules
     * @var string[]
     */
    public static $modules = [
        \AncientWorks\Artifact\Modules\Command\Command::class,
        \AncientWorks\Artifact\Modules\OxygenUnloader\Unloader::class,
        \AncientWorks\Artifact\Modules\OxygenSandbox\Sandbox::class,
        \AncientWorks\Artifact\Modules\OxygenCopyPaste\CopyPaste::class,
        \AncientWorks\Artifact\Modules\OxygenMoveWithArrow\MoveWithArrow::class,
    ];

    /**
     * The Fully qualified name main class of all artifact's exclusive modules
     * @var string[]
     */
    public static $exclusive_modules = [
        \AncientWorks\Artifact\Modules\OxygenCollaboration\Collaboration::class,
    ];

    /**
     * All enabled modules as defined on WordPress's option `artifact_module_enabled`
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
     * 
     * All artifact's modules maps
     * @var array
     */
    public static $available = [];

    public static function loader()
    {
        self::$modules = array_merge(
            self::$modules,
            self::$exclusive_modules
        );

        self::which_enabled();

        foreach (self::$modules as $module) {
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
        }

        foreach (self::$container as $key => $module) {
            $module->boot();
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

    public static function enable_module(string $module_id)
    {
        self::$enabled[$module_id] = true;
        update_option('artifact_module_enabled', self::$enabled);
    }

    public static function disable_module(string $module_id)
    {
        unset(self::$enabled[$module_id]);
        update_option('artifact_module_enabled', self::$enabled);
    }
}
