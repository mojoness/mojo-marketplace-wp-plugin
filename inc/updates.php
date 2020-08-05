<?php
/*
	Auto Update Major on New Installs and default for all other with a setting section to customize.
*/

function mm_auto_update_make_bool( $value, $default = true ) {
	if ( 'false' === $value ) {
		$value = false;
	}
	if ( 'true' === $value ) {
		$value = true;
	}
	if ( true !== $value && false !== $value ) {
		$value = $default;
	}
	return $value;
}

function mm_auto_update_callback( $args ) {
	if ( ! defined( 'AUTOMATIC_UPDATER_DISABLED' ) || AUTOMATIC_UPDATER_DISABLED === false ) {
		$defaults = array(
			'allow_major_auto_core_updates' => 'true',
			'allow_minor_auto_core_updates' => 'true',
			'auto_update_plugin'            => 'true',
			'auto_update_theme'             => 'true',
			'auto_update_translation'       => 'true',
		);
		$value    = get_option( $args['field'], $defaults[ $args['field'] ] );
		echo __( 'On', 'mojo-marketplace-wp-plugin' ) . " <input type='radio' name='" . $args['field'] . "' value='true'" . checked( $value, 'true', false ) . ' />';
		echo __( 'Off', 'mojo-marketplace-wp-plugin' ) . " <input type='radio' name='" . $args['field'] . "' value='false'" . checked( $value, 'false', false ) . ' />';
	}
}

function mm_auto_update_register_settings() {
	$section_name = 'mm_auto_update_settings_section';
	$section_hook = 'general';

	if ( 'bluehost' == mm_brand() ) {
		$brand = __( 'Bluehost', 'mojo-marketplace-wp-plugin' );
	} else {
		$brand = __( 'Host', 'mojo-marketplace-wp-plugin' );
	}

	if ( ! defined( 'AUTOMATIC_UPDATER_DISABLED' ) ) {
		$brand = get_option( 'mm_brand', 'MOJO' );
		if ( 'BlueHost' == $brand ) {
			$brand = __( 'Bluehost', 'mojo-marketplace-wp-plugin' );
		}
		add_settings_section(
			$section_name,
			$brand . ' ' . __( 'Auto Update Manager', 'mojo-marketplace-wp-plugin' ),
			'__return_false',
			$section_hook
		);
	}

	if ( ! defined( 'WP_AUTO_UPDATE_CORE' ) ) {
		add_settings_field(
			'allow_major_auto_core_updates',
			__( 'Core Major', 'mojo-marketplace-wp-plugin' ),
			'mm_auto_update_callback',
			$section_hook,
			$section_name,
			array( 'field' => 'allow_major_auto_core_updates' )
		);
		register_setting( 'general', 'allow_major_auto_core_updates' );
	}

	if ( ! defined( 'WP_AUTO_UPDATE_CORE' ) ) {
		add_settings_field(
			'allow_minor_auto_core_updates',
			__( 'Core Minor', 'mojo-marketplace-wp-plugin' ),
			'mm_auto_update_callback',
			$section_hook,
			$section_name,
			array( 'field' => 'allow_minor_auto_core_updates' )
		);
		register_setting( 'general', 'allow_minor_auto_core_updates' );
	}

	add_settings_field(
		'auto_update_theme',
		__( 'Themes', 'mojo-marketplace-wp-plugin' ),
		'mm_auto_update_callback',
		$section_hook,
		$section_name,
		array( 'field' => 'auto_update_theme' )
	);
	register_setting( 'general', 'auto_update_theme' );

	add_settings_field(
		'auto_update_plugin',
		__( 'Plugins', 'mojo-marketplace-wp-plugin' ),
		'mm_auto_update_callback',
		$section_hook,
		$section_name,
		array( 'field' => 'auto_update_plugin' )
	);
	register_setting( 'general', 'auto_update_plugin' );

	add_settings_field(
		'auto_update_translation',
		__( 'Translations', 'mojo-marketplace-wp-plugin' ),
		'mm_auto_update_callback',
		$section_hook,
		$section_name,
		array( 'field' => 'auto_update_translation' )
	);
	register_setting( 'general', 'auto_update_translation' );
}
add_action( 'admin_init', 'mm_auto_update_register_settings' );

function mm_auto_update_configure() {
	global $wp_version;

	$settings = array(
		'allow_major_auto_core_updates' => get_option( 'allow_major_auto_core_updates', true ),
		'allow_minor_auto_core_updates' => get_option( 'allow_minor_auto_core_updates', true ),
		'auto_update_translation'       => get_option( 'auto_update_translation', true ),
	);

	/*
	 * A native UI for managing plugin and theme auto-updates was added in WordPress 5.5.0.
	 * If the site is not running 5.5.0 or higher, continue to manage auto-updates through
	 * the plugin.
	 */
	if ( version_compare( $wp_version, '5.5.0', '<' ) ) {
		$settings = array_merge( $settings, array(
			'auto_update_plugin'            => get_option( 'auto_update_plugin', true ),
			'auto_update_theme'             => get_option( 'auto_update_theme', true ),
		) );
	}

	// only change setting if the updater is not disabled
	if ( ! defined( 'AUTOMATIC_UPDATER_DISABLED' ) || AUTOMATIC_UPDATER_DISABLED === false ) {
		if ( defined( 'WP_AUTO_UPDATE_CORE' ) ) {
			switch ( WP_AUTO_UPDATE_CORE ) {
				case true:
					$settings['allow_major_auto_core_updates'] = true;
					$settings['allow_minor_auto_core_updates'] = true;
					break;
				case false:
					$settings['allow_major_auto_core_updates'] = false;
					$settings['allow_minor_auto_core_updates'] = false;
					break;
				default:
					$settings['allow_major_auto_core_updates'] = true;
					$settings['allow_minor_auto_core_updates'] = true;
					break;
			}
		}

		$settings = array_map( 'mm_auto_update_make_bool', $settings );

		foreach ( $settings as $name => $value ) {
			if ( $value ) {
				add_filter( $name, '__return_true' );
			} else {
				add_filter( $name, '__return_false' );
			}
		}
	}
}
add_action( 'plugins_loaded', 'mm_auto_update_configure', 5 );

/**
 * Automatically enables auto-updates for plugins and themes when the settings are enabled.
 *
 * This runs on the @see {'upgrader_process_complete'} action, which fires when an
 * update or installation happens.
 *
 * @global $wp_version
 *
 * @param object $wp_upgrader The upgrader instance for the current process. This could be a
 *                            WP_Upgrader, Theme_Upgrader, Plugin_Upgrader, Core_Upgrade, or
 *                            Language_Pack_Upgrader class instance.
 * @param array $hook_extra {
 *     Array of bulk item update data.
 *
 *     Below is the data used in the this function. More data is potentially available, but not
 *     for the context that's targeted in this function.
 *
 *     @type string $action       Type of action. Default 'update'.
 *     @type string $type         Type of update process. Accepts 'plugin', 'theme', 'translation', or 'core'.
 * }
 */
function mm_plugin_theme_installed( $wp_upgrader, $hook_extra ) {
	global $wp_version;

	// A native UI for managing plugin and theme auto-updates was added in WordPress 5.5.0.
	if ( version_compare( $wp_version, '5.5.0', '<' ) ) {
		return;
	}

	// Only proceed if a plugin or theme is being installed.
	if ( ! in_array( $hook_extra['type'], array( 'plugin', 'theme' ), true ) ) {
		return;
	}

	if ( 'install' !== $hook_extra['action'] ) {
		return;
	}

	// Make sure hte correct Upgrader class is passed.
	if ( ! is_a( $wp_upgrader, ucfirst( $hook_extra['type'] ) . '_Upgrader' ) ) {
		return;
	}

	$auto_update_state = mm_auto_update_make_bool( get_option( "auto_update_{$hook_extra['type']}", true ) );

	// If the setting is set to off, don't configure new plugins and themes to auto-update.
	if ( ! $auto_update_state ) {
		return;
	}

	$enabled_auto_updates = get_site_option( "auto_update_{$hook_extra['type']}s", array() );

	if ( is_a( $wp_upgrader, 'Plugin_Upgrader' ) ) {
		$plugins = get_plugins();
		$plugin_file = '';
		$installed_plugin = $wp_upgrader->new_plugin_data;

		// Since the plugin file is not returned
		foreach ( $plugins as $file => $plugin ) {
			if ( $installed_plugin['Name'] !== $plugin['Name'] || $installed_plugin['PluginURI'] !== $plugin['PluginURI'] ) {
				continue;
			}

			$plugin_file = $file;
			break;
		}

		if ( empty( $plugin_file ) || ! file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
			return;
		}

		if ( in_array( $plugin_file, $enabled_auto_updates, true ) ) {
			return;
		}

		$enabled_auto_updates[] = $plugin_file;

		update_site_option( "auto_update_{$hook_extra['type']}s", array_values( array_unique( $enabled_auto_updates ) ) );

		return;
	}

	if ( is_a( $wp_upgrader, 'Theme_Upgrader' ) ) {
		if ( ! file_exists( WP_CONTENT_DIR . '/themes/' . $wp_upgrader->result['destination_name'] . '/style.css' ) ) {
			return;
		}

		$enabled_auto_updates[] = $wp_upgrader->result['destination_name'];

		update_site_option( "auto_update_{$hook_extra['type']}s", array_values( array_unique( $enabled_auto_updates ) ) );

		return;
	}
}
add_action( 'upgrader_process_complete', 'mm_plugin_theme_installed', 10, 2 );

/**
 * Checks for manual changes by the user to the auto-update settings.
 *
 * If auto-updates for an individual plugin or theme are disabled, then the on/off
 * setting can't always adjust accordingly.
 *
 * @param string $type The type of auto-update to check. Defaults to 'plugin'.
 * @return bool If there are manual changes to auto-updates for the passed type.
 *              Returns true if there are user changes, false if there are not.
 */
function mm_has_user_changes_auto_updates( $type = 'plugin' ) {
	if ( 'theme' === $type ) {
		$auto_updating_themes = sort( get_site_option( "auto_update_themes", array() ) );
		$installed_themes = sort( array_keys( wp_get_themes() ) );

		return $auto_updating_themes !== $installed_themes;
	}

	$installed_plugins = sort( array_keys( get_plugins() ) );
	$auto_updating_plugins = sort( get_site_option( "auto_update_plugins", array() ) );

	return $auto_updating_plugins !== $installed_plugins;
}
