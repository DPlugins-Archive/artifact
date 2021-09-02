<?php

namespace AncientWorks\Artifact;

use AncientWorks\Artifact\Admin\DashboardController;
use AncientWorks\Artifact\Admin\ModuleController;
use AncientWorks\Artifact\Admin\SettingController;

/**
 * @package AncientWorks\Artifact
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 * @copyright 2021 ancientworks
 */
class Admin
{
	public static $enqueue_styles = [];
	public static $enqueue_scripts = [];
	public static $localize_scripts = [];

	public function __construct()
	{
		add_action('admin_enqueue_scripts', function () {
			wp_register_style('artifact/jetpack-admin', 'https://cdn.jsdelivr.net/wp/plugins/jetpack/trunk/css/jetpack-admin.min.css');
			wp_register_style('artifact/jetpack-dops', 'https://cdn.jsdelivr.net/wp/plugins/jetpack/trunk/_inc/build/admin.css');
			wp_register_style('artifact/jetpack-components', 'https://cdn.jsdelivr.net/wp/plugins/jetpack/trunk/_inc/build/style.min.css', [
				'artifact/jetpack-admin',
				'artifact/jetpack-dops'
			]);

			wp_register_script('artifact/dashboard', plugins_url('/dist/js/dashboard.js', ARTIFACT_FILE), [], false, true);
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
				[$this, 'route'],
				'data:image/svg+xml;base64,' . base64_encode(@file_get_contents(dirname(ARTIFACT_FILE) . '/dist/img/artifact.svg')),
				100
			);

			add_action('load-' . $hook, [$this, 'init_hooks']);
		}
	}

	public function init_hooks(): void
	{
		add_filter('admin_body_class', function ($classes) {
			$classes .= ' jetpack-disconnected jetpack-pagestyles';

			if (isset($_REQUEST['route']) && $_REQUEST['route'] === 'modules') {
				$classes .= ' admin_page_jetpack_modules';
			}

			return $classes;
		});

		add_action('admin_enqueue_scripts', function () {
			wp_enqueue_style('artifact/jetpack-components');
		});

		$route = $_REQUEST['route'] ?? 'dashboard';
		switch ($route) {
			case 'dashboard':
				add_action('admin_enqueue_scripts', function () {
					foreach (self::$enqueue_styles as $style) {
						wp_enqueue_style($style);
					}
					foreach (self::$enqueue_scripts as $script) {
						wp_enqueue_script($script);
					}
					foreach (self::$localize_scripts as $localize_script) {
						wp_localize_script(
							$localize_script['handle'],
							$localize_script['object_name'],
							(is_callable($localize_script['l10n'])
								? call_user_func($localize_script['l10n'])
								: $localize_script['l10n'])
						);
					}
				});
				break;
			default:

				break;
		}
	}

	public function route()
	{
		$route = $_REQUEST['route'] ?? 'dashboard';
		switch ($route) {
			case 'about':
				# code...
				break;

			case 'modules':
				(new ModuleController)();
				break;

			case 'settings':
				(new SettingController)();
				break;

			case 'dashboard':
			default:
				(new DashboardController)();
				break;
		}
	}
}
