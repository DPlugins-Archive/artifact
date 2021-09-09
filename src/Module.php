<?php

namespace AncientWorks\Artifact;

use AncientWorks\Artifact\Utils\Template;
use AncientWorks\Artifact\Utils\Utils;
use Composer\Semver\Comparator;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 * @copyright 2021 ancientworks
 */
abstract class Module
{
    public static $module_id;
    public static $module_version;
    public static $module_name = false;

    public function __construct()
    {
        $this->load_template_folders();

        $this->register();

        if (!ModuleProvider::is_enabled(static::$module_id)) {
            return;
        }
    }

    public function register()
    {
    }

    public function boot()
    {
    }

    public function load_template_folders()
    {
        $this->load_template_extentions();

        $template_dir = dirname((new \ReflectionClass(static::class))->getFileName()) . '/templates';

        if (is_dir($template_dir)) {
            Template::template()->addFolder(
                static::$module_id,
                $template_dir
            );
        }
    }

    public function load_template_extentions()
    {
        $template_extention = new \ReflectionClass(static::class) . '\TemplateExtension';

        if (
            class_exists($template_extention)
            && (new \ReflectionClass($template_extention))->implementsInterface(\League\Plates\Extension\ExtensionInterface::class)
        ) {
            Template::template()->loadExtension(new $template_extention);
        }
    }

    public function get_installed_version()
    {
        return Utils::get_option('installed_module_version_' . static::$module_id);
    }

    public function set_installed_version()
    {
        return Utils::update_option('installed_module_version_' . static::$module_id, static::$module_version);
    }

    public function install(): bool
    {
        return $this->set_installed_version();
    }

    public function uninstall(): bool
    {
        return true;
    }

    public function upgrade(): bool
    {
        return $this->set_installed_version();
    }

    public function downgrade(): bool
    {
        return true;
    }

    public function activate(): bool
    {
        $installed_module_version = $this->get_installed_version();

        if (!$installed_module_version) {
            $this->install();
        }

        if (Comparator::lessThan($installed_module_version, static::$module_version)) {
            $this->upgrade();
        }

        return true;
    }

    public function deactivate(): bool
    {
        return true;
    }
}
