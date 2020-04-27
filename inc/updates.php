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

	$settings = array(
		'allow_major_auto_core_updates' => get_option( 'allow_major_auto_core_updates', true ),
		'allow_minor_auto_core_updates' => get_option( 'allow_minor_auto_core_updates', true ),
		'auto_update_plugin'            => get_option( 'auto_update_plugin', true ),
		'auto_update_theme'             => get_option( 'auto_update_theme', true ),
		'auto_update_translation'       => get_option( 'auto_update_translation', true ),
	);

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
