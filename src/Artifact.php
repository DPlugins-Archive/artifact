<?php

namespace AncientWorks\Artifact;

use AncientWorks\Artifact\Utils\Utils;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 * @copyright 2021 ancientworks
 */
class Artifact
{
    public static $version;
    public static $slug = 'artifact';

    public static $updater;

    public function __construct($version)
    {
        self::$version = $version;

        register_activation_hook(ARTIFACT_FILE, [$this, 'plugin_activate']);
        register_deactivation_hook(ARTIFACT_FILE, [$this, 'plugin_deactivate']);

        self::$updater = new PluginUpdater(self::$slug, [
            'version'     => self::$version,
            'license'     => get_option(self::$slug . "_license_key"),
            'beta'        => get_option(self::$slug . "_beta"),
            'plugin_file' => ARTIFACT_FILE,
            'item_id'     => 1,
            'store_url'   => 'https://ancient.works',
            'author'      => 'ancientworks',
            'is_require_license' => false,
        ]);

        ModuleProvider::loader();

        # code...

        new Admin;

        add_action('init', [$this, 'init']);

        add_filter('plugin_action_links_' . plugin_basename(ARTIFACT_FILE), function ($links) {
            return Utils::plugin_action_links($links);
        });
    }

    public function init()
    {
        self::plugin_update();
    }

    protected static function plugin_update()
    {
        if (self::$updater->isActivated()) {
            $doing_cron = defined('DOING_CRON') && DOING_CRON;
            if (!(current_user_can('manage_options') && $doing_cron)) {
                self::$updater->ignite();
            }
        }
    }

    public static function run($version)
    {
        static $instance = false;

        if (!$instance) {
            $instance = new Artifact($version);
        }

        return $instance;
    }

    public function plugin_activate()
    {
    }
    public function plugin_deactivate()
    {
    }
}
