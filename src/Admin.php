<?php

namespace AncientWorks\Artifact;

use AncientWorks\Artifact\Utils\Template;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 * @copyright 2021 ancientworks
 */
class Admin
{
	public function __construct()
	{
		add_action('admin_enqueue_scripts', function () {
			wp_register_style('artifact-jetpack-dops', 'https://cdn.jsdelivr.net/wp/plugins/jetpack/trunk/_inc/build/admin.css');
			wp_register_style('artifact-jetpack-components', 'https://cdn.jsdelivr.net/wp/plugins/jetpack/trunk/_inc/build/style.min.css', [
				'artifact-jetpack-dops'
			]);
		});

		add_action('admin_menu', [$this, 'admin_menu']);

	}

	public function admin_menu()
	{
		$capability = 'manage_options';

		if (current_user_can($capability)) {
			$hook = add_menu_page(
				'Artifact',
				'Artifact',
				$capability,
				Artifact::$slug,
				[$this, 'plugin_page'],
				'data:image/svg+xml;base64,' . base64_encode(@file_get_contents(dirname(ARTIFACT_FILE) . '/dist/img/artifact.svg')),
				100
			);

			add_action('load-' . $hook, [$this, 'init_hooks']);
		}
	}

	public function init_hooks(): void
	{
		add_filter('admin_body_class', fn($classes) => $classes . ' jetpack-disconnected jetpack-pagestyles');

		add_action('admin_enqueue_scripts', function () {
			wp_enqueue_style('artifact-jetpack-components');
		});
	}

	public function plugin_page() {
		// echo Template::template()->render('pages/admin/dashboard');
	}
}
