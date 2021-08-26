<?php

namespace AncientWorks\Artifact\Utils;

/**
 * @since 0.0.1
 * @author ancientworks <mail@ancient.works>
 */
class OxygenBuilder
{
	public static function is_oxygen_editor(): bool {
		return defined( 'SHOW_CT_BUILDER' ) && ! defined( 'OXYGEN_IFRAME' );
	}

	public static function is_oxygen_iframe(): bool {
		return defined( 'SHOW_CT_BUILDER' ) && defined( 'OXYGEN_IFRAME' );
	}

	/**
	 * @see \oxygen_vsb_current_user_can_access
	 * @see \oxygen_vsb_current_user_can_full_access
	 */
	public static function can( bool $full = false ): bool {
		if ( is_multisite() && is_super_admin() ) {
			return true;
		}

		$user = wp_get_current_user();

		if ( ! $user ) {
			return false;
		}

		$user_edit_mode = self::get_user_edit_mode();

		if ( $full ) {
			if ( $user_edit_mode === "true" ) {
				return true;
			} else if ( $user_edit_mode === "false" || $user_edit_mode == 'edit_only' ) {
				return false;
			}
		} else {
			if ( $user_edit_mode === "true" || $user_edit_mode == 'edit_only' ) {
				return true;
			} else if ( $user_edit_mode === "false" ) {
				return false;
			}
		}

		if ( $user && isset( $user->roles ) && is_array( $user->roles ) ) {
			foreach ( $user->roles as $role ) {
				if ( $role === 'administrator' ) {
					return true;
				}
				$option = get_option( "oxygen_vsb_access_role_{$role}", false );
				if ( $option && $option === 'true' ) {
					return true;
				}

				if ( $full ) {
					if ( $option && $option == 'true' ) {
						return true;
					}
				} else {
					if ( $option && ( $option == 'true' || $option == 'edit_only' ) ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * @see \oxygen_vsb_get_user_edit_mode
	 */
	public static function get_user_edit_mode( bool $skip_role = false ) {
		$user_id           = get_current_user_id();
		$users_access_list = get_option( "oxygen_vsb_options_users_access_list", [] );

		if ( isset( $users_access_list[ $user_id ] ) && isset( $users_access_list[ $user_id ][0] ) ) {
			return $users_access_list[ $user_id ][0];
		}

		if ( $skip_role ) {
			return "";
		}

		$user = wp_get_current_user();

		if ( ! $user ) {
			return "";
		}

		$edit_only = false;

		if ( $user && isset( $user->roles ) && is_array( $user->roles ) ) {
			foreach ( $user->roles as $role ) {
				if ( $role == 'administrator' ) {
					return "true";
				}
				$option = get_option( "oxygen_vsb_access_role_$role", false );
				if ( $option && $option == 'true' ) {
					return "true";
				}

				if ( $option && $option == 'edit_only' ) {
					$edit_only = true;
				}
			}
		}

		if ( $edit_only ) {
			return "edit_only";
		}

		return "";
	}

	/**
	 * @see \oxygen_is_user_access_option_set
	 */
	public static function is_user_access_option_set( string $option_name ): bool {
		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return false;
		}

		$access_option = get_option( $option_name, [] );

		if ( isset( $access_option[ $user_id ] ) && isset( $access_option[ $user_id ][0] ) && $access_option[ $user_id ][0] === "true" ) {
			return true;
		}

		return false;
	}

	/**
	 * @see \oxygen_is_role_access_option_set
	 */
	public static function is_role_access_option_set( string $option_name ): bool {
		$user = wp_get_current_user();

		if ( ! $user ) {
			return false;
		}

		if ( $user && isset( $user->roles ) && is_array( $user->roles ) ) {
			$option = get_option( $option_name, false );
			foreach ( $user->roles as $role ) {
				if ( $role == 'administrator' ) {
					return true;
				}
				if ( isset( $option[ $role ] ) && isset( $option[ $role ][0] ) && $option[ $role ][0] === "true" ) {
					return true;
				}
			}
		}

		return false;
	}
}