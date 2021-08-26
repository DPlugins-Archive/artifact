<?php

/**
 * mu unload oxygen
 *
 * @wordpress-plugin
 * Plugin URI:          https://ancient.works/artifact
 * Description:         mu unload oxygen
 * Version:             0.0.1
 * Requires at least:   5.8
 * Tested up to:        5.8
 * Requires PHP:        7.4
 * Author:              ancientworks
 * Author URI:          https://ancient.works
 * Text Domain:         artifact
 * Domain Path:         /languages
 *
 * @package             AncientWorks\Artifact
 * @author              ancientworks <mail@ancient.works>
 * @link                https://ancient.works
 * @since               0.0.1
 * @copyright           2021 ancientworks
 * @version             0.0.1
 */

defined( 'ABSPATH' ) || exit;

require_once WP_PLUGIN_DIR . '/ancientworks-artifact/vendor/autoload.php';

add_filter( 'option_active_plugins', function($active_plugins) {
    require_once ABSPATH . 'wp-includes/pluggable.php';

    if ( ! \AncientWorks\Artifact\Utils\OxygenBuilder::can() ) {
        $oxygen_index = array_search( 'oxygen/functions.php', $active_plugins );

        if ( false !== $oxygen_index ) {
            array_splice( $active_plugins, $oxygen_index, 1 );
        }
    }

    return $active_plugins;
} );
