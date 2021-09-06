<?php

namespace AncientWorks\Artifact\Admin;

use AncientWorks\Artifact\Artifact;
use AncientWorks\Artifact\Utils\Template;
use AncientWorks\Artifact\Utils\Utils;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 * @copyright 2021 ancientworks
 */
class DashboardController
{
    public static $panel = [];

    public static function registerModulePanel(string $size, string $module_id, string $panel_template, callable $panel_handle)
    {
        self::$panel[$module_id] = [
            'size' => $size,
            'panel_template' => $panel_template,
            'panel_handle' => $panel_handle,
        ];
    }

    public function __invoke()
    {
        if (
            !empty($_REQUEST['action'])
            && !empty($_REQUEST['module_id'])
            && array_key_exists($_REQUEST['module_id'], self::$panel)
            && !empty($_REQUEST['_wpnonce'])
            && wp_verify_nonce($_REQUEST['_wpnonce'], Artifact::$slug)
        ) {
            $handled = call_user_func(self::$panel[$_REQUEST['module_id']]['panel_handle']);
            if ($handled) {
                Utils::redirect($_SERVER['HTTP_REFERER']);
            }
        }

        echo Template::template()->render('pages/admin/dashboard');
    }
}
