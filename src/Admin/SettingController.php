<?php

namespace AncientWorks\Artifact\Admin;

use AncientWorks\Artifact\Artifact;
use AncientWorks\Artifact\Utils\Notice;
use AncientWorks\Artifact\Utils\Template;
use AncientWorks\Artifact\Utils\Utils;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 * @copyright 2021 ancientworks
 */
class SettingController
{
    public function __invoke()
    {
        if (
            !empty($_REQUEST['action'])
            && !empty($_REQUEST['_wpnonce'])
            && wp_verify_nonce($_REQUEST['_wpnonce'], Artifact::$slug)
        ) {
            $handled = $this->action();
            if ($handled) {
                Utils::redirect($_SERVER['HTTP_REFERER']);
            }
        }

        echo Template::template()->render('pages/admin/settings');
    }

    protected function action()
    {
        switch ($_REQUEST['action']) {
            case 'save_license':
                $this->handleSaveLicense();
                return true;
                break;

            default:
                # code...
                break;
        }
    }

    protected function handleSaveLicense()
    {
        Utils::update_option("_beta", sanitize_text_field($_REQUEST['beta'] ?? false));

        $_request_license_key = sanitize_text_field($_REQUEST['license_key']);

        $stored_license = Utils::get_option("_license_key");
        if ($_request_license_key !== $stored_license) {
            if (empty($_request_license_key)) {
                if (!empty($stored_license)) {
                    Artifact::$updater->deactivate();
                    Utils::update_option("_license_key", null);

                    return Notice::success('Plugin license key de-activated successfully');
                }
            } else {
                $response = Artifact::$updater->activate($_request_license_key);

                if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
                    return Notice::error(is_wp_error($response) ? $response->get_error_message() : 'An error occurred, please try again.');
                } else {
                    $license_data = json_decode(wp_remote_retrieve_body($response));

                    if ($license_data->license != 'valid') {
                        return Notice::error(Artifact::$updater::errorMessage($license_data->error));
                    } else {
                        Utils::update_option("_license_key", $_request_license_key);
                        return Notice::success('Plugin license key activated successfully');
                    }
                }
            }
        }

        Notice::success('Setting saved!');
    }
}
