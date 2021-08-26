<?php

/**
 * Artifact
 *
 * @wordpress-plugin
 * Plugin Name:         Artifact
 * Plugin URI:          https://ancient.works/artifact
 * Description:         Oxygen Builder better workflow and environment
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
 * @link                https://ancient.works/artifact
 * @since               0.0.1
 * @copyright           2021 ancientworks
 * @version             0.0.1
 */

defined('ABSPATH') || exit;

define('ARTIFACT_FILE', __FILE__);

require_once __DIR__ . '/vendor/autoload.php';

\AncientWorks\Artifact\Artifact::run('0.0.1');
