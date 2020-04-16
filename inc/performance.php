<?php

function mm_cache_toggle() {
	if ( isset( $_POST['cache_level'] ) && is_numeric( $_POST['cache_level'] ) ) {
		$cache_level = (int) $_POST['cache_level'];
		$response    = mm_cache_add( 'page' );

		if ( isset( $response['status'] ) && 'success' == $response['status'] ) {
			$update = update_option( 'endurance_cache_level', $cache_level );
			if ( true == $update ) {
				$response = array(
					'status'  => 'success',
					'message' => esc_html__( 'Cache level updated successfully.', 'mojo-marketplace-wp-plugin' ),
				);
			} else {
				$response = array(
					'status'  => 'error',
					'message' => esc_html__( 'Unable to update cache level.', 'mojo-marketplace-wp-plugin' ),
				);
			}
		} else {
			$response = array(
				'status'  => 'error',
				'message' => esc_html__( 'Unable to add cache plugin.', 'mojo-marketplace-wp-plugin' ),
			);
		}

		echo json_encode( $response );
	}
	die;
}
add_action( 'wp_ajax_mm_cache', 'mm_cache_toggle' );

function mm_cache_add( $type = null ) {
	$cache = array();
	if ( ! is_dir( WP_CONTENT_DIR . '/mu-plugins' ) ) {
		mkdir( WP_CONTENT_DIR . '/mu-plugins' );
	}
	switch ( $type ) {
		case 'page':
			$cache['code']     = 'https://raw.githubusercontent.com/bluehost/endurance-page-cache/production/endurance-page-cache.php';
			$cache['location'] = WP_CONTENT_DIR . '/mu-plugins/endurance-page-cache.php';
			break;

		case 'object':
			if ( class_exists( 'memcached' ) || class_exists( 'memcache' ) ) {
				$response = array(
					'status'  => 'error',
					'message' => esc_html__( 'Object cache coming soon.', 'mojo-marketplace-wp-plugin' ),
				);
			} else {
				$response = array(
					'status'  => 'error',
					'message' => esc_html__( 'Object cache not available on your hosting plan.', 'mojo-marketplace-wp-plugin' ),
				);
			}
			break;
	}
	if ( isset( $cache['code'] ) && isset( $cache['location'] ) ) {
		$request = wp_remote_get( $cache['code'] );
		if ( ! is_wp_error( $request ) ) {
			file_put_contents( $cache['location'], $request['body'] );
			if ( file_exists( $cache['location'] ) ) {
				$response = array(
					'status'  => 'success',
					'message' => sprintf(
						/* translators: %s: type of cache */
						esc_html__( '%s cache added successfully.', 'mojo-marketplace-wp-plugin' ),
						ucfirst( $type )
					),
				);
			}
		}
	}

	if ( ! isset( $response ) ) {
		$response = array(
			'status'  => 'error',
			'message' => sprintf(
				/* translators: %s: type of cache */
				esc_html__( 'Unable to add %s cache.', 'mojo-marketplace-wp-plugin' ),
				ucfirst( $type )
			),
		);
	}
	return $response;

}

function mm_cache_remove( $type = null ) {
	switch ( $type ) {
		case 'browser':
			// do not remove cache file since it powers both types and the CDN.
			break;
		case 'page':
			// do not remove cache file since it powers both types and the CDN.
			break;
		case 'object':
			$file = WP_CONTENT_DIR . '/object-cache.php';
			break;
	}
	if ( file_exists( $file ) ) {
		if ( unlink( $file ) ) {
			$response = array(
				'status'  => 'success',
				'message' => sprintf(
					/* translators: %s: type of cache */
					esc_html__( '%s cache removed successfully.', 'mojo-marketplace-wp-plugin' ),
					ucfirst( $type )
				),
			);
		} else {
			$response = array(
				'status'  => 'error',
				'message' => esc_html__( 'Could not remove cache file.', 'mojo-marketplace-wp-plugin' ),
			);
		}
	} else {
		$response = array(
			'status'  => 'error',
			'message' => esc_html__( 'Cache file does not exist.', 'mojo-marketplace-wp-plugin' ),
		);
	}
	return $response;
}

function mm_php_edge_toggle() {

	if ( isset( $_POST['php_level'] ) ) {

		$new_php_setting = $_POST['php_level'];
		$php_setting     = get_option( 'mm_php_edge_settings', '56' );

		if ( isset( $new_php_setting ) && ! empty( $new_php_setting ) ) {
			$php_handler_response = mm_php_edge_add( $new_php_setting );
		} else {
			$php_handler_response = mm_php_edge_remove();
		}

		if ( 'success' == $php_handler_response['status'] ) {
			update_option( 'mm_php_edge_settings', $new_php_setting );
		}
		echo json_encode( $php_handler_response );
	}
	die;
}
add_action( 'wp_ajax_mm_php_edge', 'mm_php_edge_toggle' );


function mm_php_edge_add( $new_php_setting, $type = null ) {
	$php_edge = array();
	if ( ! is_dir( WP_CONTENT_DIR . '/mu-plugins' ) ) {
		mkdir( WP_CONTENT_DIR . '/mu-plugins' );
	}
	$php_edge['code']     = 'https://raw.githubusercontent.com/bluehost/endurance-php-edge/master/endurance-php-edge.php';
	$php_edge['location'] = WP_CONTENT_DIR . '/mu-plugins/endurance-php-edge.php';
	$request              = wp_remote_get( $php_edge['code'] );
	if ( ! is_wp_error( $request ) ) {
		// file_put_contents( $php_edge['location'], $request['body'] );
		save_mod_rewrite_rules();
		if ( file_exists( $php_edge['location'] ) ) {
			$php_check = wp_remote_get( site_url() );

			$updated_php_version = '';
			switch ( $new_php_setting ) {
				case '70':
					$updated_php_version = '7.0';
					break;
				case 'edge':
					$updated_php_version = 'Edge';
					break;

				default:
					$updated_php_version = '5.6';
					break;
			}

			if ( ! is_wp_error( $php_check ) && wp_remote_retrieve_response_code( $php_check ) == '200' ) {
				$response = array(
					'status'  => 'success',
					'message' => sprintf(
						/* translators: %s: PHP version */
						esc_html__( 'PHP is updated to %s', 'mojo-marketplace-wp-plugin' ),
						$updated_php_version
					),
				);
			} else {
				$response = array(
					'status'  => 'error',
					'message' => esc_html__( 'Site is incompatible with PHP Edge. Edge removed.', 'mojo-marketplace-wp-plugin' ),
				);
				mm_php_edge_remove();
				save_mod_rewrite_rules();
			}
		}
	} else {
		$response = array(
			'status'  => 'error',
			'message' => esc_html__( 'Unable to enable PHP Edge.', 'mojo-marketplace-wp-plugin' ),
		);
	}
	return $response;
}


function mm_php_edge_remove( $type = null ) {
	$file = WP_CONTENT_DIR . '/mu-plugins/endurance-php-edge.php';
	if ( file_exists( $file ) ) {
		if ( unlink( $file ) ) {
			$response = array(
				'status'  => 'success',
				'message' => esc_html__( 'PHP Edge successfully disabled.', 'mojo-marketplace-wp-plugin' ),
			);
		} else {
			$response = array(
				'status'  => 'error',
				'message' => esc_html__( 'Unable to remove PHP Edge.', 'mojo-marketplace-wp-plugin' ),
			);
		}
	} else {
		$response = array(
			'status'  => 'error',
			'message' => esc_html__( 'PHP Edge file does not exist.', 'mojo-marketplace-wp-plugin' ),
		);
	}
	return $response;
}
