<?php

namespace AncientWorks\Artifact\Modules\OxygenUnloader;

use AncientWorks\Artifact\Module;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 */
class Unloader extends Module
{
    public static $module_id = 'oxygen_unloader';
    public static $module_version = '0.0.1';

    protected static $mu_filename = 'mu_unload_oxygen.php';

    public function install_mu()
    {
        $this->uninstall_mu();

        if (!file_exists(WPMU_PLUGIN_DIR) || !is_dir(WPMU_PLUGIN_DIR)) {
            mkdir(WPMU_PLUGIN_DIR);
        }

        copy(
            plugin_dir_path(__FILE__) . '/' . self::$mu_filename,
            WPMU_PLUGIN_DIR . '/' . self::$mu_filename
        );
    }

    public function uninstall_mu()
    {
        if (file_exists(WPMU_PLUGIN_DIR . '/' . self::$mu_filename)) {
            unlink(WPMU_PLUGIN_DIR . '/' . self::$mu_filename);
        }
    }
}
