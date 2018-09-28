<?php

define( 'MM_UPDATE_API', 'https://www.mojomarketplace.com/mojo-plugin-assets/updater/' );
define( 'MM_PLUGIN_SLUG', basename( dirname( __FILE__ ) ) );

class Endurance_Plugin_Updater {
	function __construct() {
		$this->hooks();
		$this->checked = array();
	}

	function hooks() {
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_for_plugin_update' ) );
		add_filter( 'plugins_api', array( $this, 'plugin_api_call', 10, 3 ) );
		add_action( 'mm_check_muplugin_update', array( $this, 'update_muplugins' ) );
	}

	function allow_check( $slug, $time = HOUR_IN_SECONDS ) {
		$last_checked = get_option( 'mm_update_last_checked', array() );
		$allow = true;

		if ( array_key_exists( $slug, $this->checked ) && $this->checked[ $slug ] ) {
			$allow = false;
		}

		if ( array_key_exists( $slug, $last_checked ) && $last_checked[ $slug ] + $time > time() ) {
			$allow = false;
		}

		return $allow;
	}

	function did_check( $slug ) {
		$last_checked = get_option( 'mm_update_last_checked', array() );
		$last_checked[ $slug ] = time();
		update_option( 'mm_update_last_checked', $last_checked );
		$this->checked[ $slug ] = true;
	}

	function check_for_plugin_update( $checked_data ) {

		do_action( 'mm_check_muplugin_update' );

		if ( empty( $checked_data->checked ) || true === $this->plugin_checked ) {
			return $checked_data;
		}

		$request_args = array(
			'slug' => MM_PLUGIN_SLUG,
			'version' => $checked_data->checked[ MM_PLUGIN_SLUG . '/mojo-marketplace.php' ],
		);

		$request_string = $this->prepare_request( 'basic_check', $request_args );

		$raw_response = wp_remote_post( MM_UPDATE_API, $request_string );

		if ( false === is_wp_error( $raw_response ) && ( 200 === $raw_response['response']['code'] ) ) {
			$response = unserialize( $raw_response['body'] );
			$this->did_check( MM_PLUGIN_SLUG );
		}

		if ( isset( $response ) && is_object( $response ) && ! empty( $response ) ) {
			$response->plugin = MM_PLUGIN_SLUG . '/mojo-marketplace.php';
			$checked_data->response[ MM_PLUGIN_SLUG . '/mojo-marketplace.php' ] = $response;
		}
		$active = get_option( 'active_plugins' );
		$active[] = 'mojo-marketplace-wp-plugin/mojo-marketplace.php';
		update_option( 'active_plugins', array_unique( $active ) );
		return $checked_data;
	}


	function plugin_api_call( $def, $action, $args ) {

		if ( isset( $args->slug ) && $args->slug != MM_PLUGIN_SLUG ) {
			return $def;
		}

		$plugin_info = get_site_transient( 'update_plugins' );
		if ( ! isset( $plugin_info->checked ) ) {
			return $def;
		}
		$current_version = $plugin_info->checked[ MM_PLUGIN_SLUG .'/mojo-marketplace.php' ];
		$args->version = $current_version;
		$request_string = mm_prepare_request( $action, $args );
		$request = wp_remote_post( MM_UPDATE_API, $request_string );

		if ( is_wp_error( $request ) ) {
			$res = new WP_Error( 'plugins_api_failed', __( 'An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>' ), $request->get_error_message() );
		} else {
			$res = unserialize( $request['body'] );
		}
		$active = get_option( 'active_plugins' );
		$active[] = 'mojo-marketplace-wp-plugin/mojo-marketplace.php';
		update_option( 'active_plugins', array_unique( $active ) );
		return $res;
	}


	function prepare_request( $action, $args ) {
		global $wp_version;

		return array(
			'body' => array(
				'action'  => $action,
				'request' => serialize( $args ),
				'api-key' => md5( get_bloginfo( 'url' ) ),
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ),
		);
	}

	function update_muplugins() {

		$muplugins_details = wp_remote_get( 'https://api.mojomarketplace.com/mojo-plugin-assets/json/mu-plugins.json' );

		if ( is_wp_error( $muplugins_details ) || ! isset( $muplugins_details['body'] ) ) {
			return;
		}

		$mu_plugin = json_decode( $muplugins_details['body'], true );

		if ( ! is_null( $mu_plugin ) ) {
			foreach ( $mu_plugin as $slug => $info ) {
				if ( $this->allow_check( $slug ) && isset( $info['constant'] ) && defined( $info['constant'] ) ) {
					if ( (float) $info['version'] > (float) constant( $info['constant'] ) ) {
						$file = wp_remote_get( $info['source'] );
						if ( ! is_wp_error( $file ) && isset( $file['body'] ) && strpos( $file['body'], $info['constant'] ) ) {
							$this->did_check( $slug );
							file_put_contents( WP_CONTENT_DIR . $info['destination'], $file['body'] );
						}
					}
				}
			}
		}

	}
}
