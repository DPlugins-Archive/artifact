<?php

namespace AncientWorks\Artifact\Admin;

use AncientWorks\Artifact\ModuleProvider;
use AncientWorks\Artifact\Utils\Notice;
use AncientWorks\Artifact\Utils\Template;
use AncientWorks\Artifact\Utils\Utils;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 * @copyright 2021 ancientworks
 */
class ModuleController
{
    public function __invoke()
    {
        if (
            !empty($_REQUEST['action'])
            && !empty($_REQUEST['_wpnonce'])
            && wp_verify_nonce($_REQUEST['_wpnonce'], 'artifact')
        ) {
            $handled = $this->action();
            if ($handled) {
                return $handled;
            }
        }

        echo Template::template()->render('pages/admin/modules');
    }

    protected function action()
    {
        switch ($_REQUEST['action']) {
            case 'activate':
                return $this->handleActivate();
                break;

            case 'deactivate':
                return $this->handleDeactivate();
                break;

            default:
                # code...
                break;
        }

        return false;
    }

    protected function handleActivate()
    {
        if (
            empty($_REQUEST['module_id'])
            || !array_key_exists($_REQUEST['module_id'], ModuleProvider::$available)
            || !array_key_exists($_REQUEST['module_id'], ModuleProvider::$shadow)

        ) {
            return false;
        }

        if (ModuleProvider::$shadow[$_REQUEST['module_id']]->activate()) {
            ModuleProvider::enable_module($_REQUEST['module_id']);
        }

        Notice::success("Module <b>{$_REQUEST['module_id']}</b> activated");

        Utils::redirect($_SERVER['HTTP_REFERER']);
    }

    protected function handleDeactivate()
    {
        if (
            empty($_REQUEST['module_id'])
            || !array_key_exists($_REQUEST['module_id'], ModuleProvider::$available)
            || !array_key_exists($_REQUEST['module_id'], ModuleProvider::$container)

        ) {
            return false;
        }

        if (ModuleProvider::$container[$_REQUEST['module_id']]->deactivate()) {
            ModuleProvider::disable_module($_REQUEST['module_id']);
        }

        Notice::success("Module <b>{$_REQUEST['module_id']}</b> deactivated");

        Utils::redirect($_SERVER['HTTP_REFERER']);
    }
}
