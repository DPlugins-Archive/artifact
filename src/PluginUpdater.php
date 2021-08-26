<?php

namespace AncientWorks\Artifact;

use AncientWorks\Artifact\Utils\EDD_SL_Plugin_Updater;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 * @copyright 2021 ancientworks
 */
class PluginUpdater {
	private $payload = [];
	private $plugin_id;

	public function __construct( $plugin_id, array $payload ) {
		$this->payload   = $payload;
		$this->plugin_id = $plugin_id;
	}

	public function ignite() {
		return new EDD_SL_Plugin_Updater( $this->payload['store_url'], $this->payload['plugin_file'], $this->payload );
	}

	public function isActivated() {
		$license = get_transient( "{$this->plugin_id}_license_seed" );

		if ( ! $license && empty( $this->payload['license'] ) ) {
			if ( array_key_exists( 'is_require_license', $this->payload ) && $this->payload['is_require_license'] ) {
				// Notice::error( 'Enter your license key to get update', $this->plugin_id );
			}

			return false;
		}

		if ( $license ) {
			if ( $license->license !== 'valid' ) {
				// Notice::error( [
				// 	'Enter your license key to get update',
				// 	$this->errorMessage( $license->license )
				// ], $this->plugin_id );

				return false;
			}

			return $license;
		}

		$response = $this->apiRequest( 'check_license' );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			// Notice::error( is_wp_error( $response ) ? $response->get_error_message() : 'An Updater error occurred, please try again.', $this->plugin_id );

			return false;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $license_data->success === false ) {
			if ( property_exists( $license_data, 'error' ) ) {
				// Notice::error( self::errorMessage( $license_data->error ), $this->plugin_id );
			}

			return false;
		}

		set_transient( "{$this->plugin_id}_license_seed", $license_data, 60 * 60 * 24 );

		return $license_data;
	}

	private function apiRequest( $action ) {
		return wp_remote_post( $this->payload['store_url'], [
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => [
				'edd_action'  => $action,
				'license'     => $this->payload['license'] ?? '',
				'item_id'     => $this->payload['item_id'] ?? false,
				'version'     => $this->payload['version'] ?? false,
				'slug'        => basename( $this->payload['plugin_file'], '.php' ),//$this->payload['plugin_file'],
				'author'      => $this->payload['author'],
				'url'         => site_url(),
				'beta'        => $this->payload['beta'] ?? false,
				'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
			]
		] );
	}

	public function deactivate() {
		delete_transient( "{$this->plugin_id}_license_seed" );

		return $this->apiRequest( 'deactivate_license' );
	}

	public function activate( ?string $license ) {
		if ( $license ) {
			$this->payload['license'] = $license;
		}

		delete_transient( "{$this->plugin_id}_license_seed" );

		return $this->apiRequest( 'activate_license' );
	}

	public static function errorMessage( $msgcode ) {
		switch ( $msgcode ) {
			case 'expired' :
				return 'Your license key expired';
			case 'disabled' :
			case 'revoked' :
				return 'Your license key has been disabled.';
			case 'inactive' :
			case 'site_inactive' :
				return 'Your license is not active for this URL.';
			case 'missing_url' :
				return 'License doesn\'t exist or URL not provided.';
			case 'key_mismatch' :
			case 'missing' :
			case 'invalid' :
			case 'invalid_item_id' :
			case 'item_name_mismatch' :
				return 'Invalid license key';
			case 'no_activations_left':
				return 'Your license key has reached its activation limit.';
			default :
				return 'An error occurred on update, please try again.';
		}
	}
}