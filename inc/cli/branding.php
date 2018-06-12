<?php

/**
 * Class Bluehost_CLI_Branding
 *
 * This class is instantiated in /inc/cli-init.php
 */
class Bluehost_CLI_Branding extends EIG_WP_CLI_Command {
	/**
	 * Update the appearance of the plugin branding.
	 *
	 * @param  null $args Unused.
	 * @param  array $assoc_args Remove or Update with new value.
	 */
	public function branding( $args, $assoc_args ) {
		if ( isset( $assoc_args['update'] ) ) {
			$brands = mm_api_cache( MM_ASSETS_URL . 'json/branding.json' );
			if ( ! is_wp_error( $brands ) && $brands = json_decode( $brands['body'] ) ) {
				$brands = (array) $brands;
				while ( false !== ( $brand = array_search( 'default', $brands ) ) ) {
					unset( $brands[ $brand ] );
				}
				$valid_brands = array_keys( $brands );
				if ( in_array( $assoc_args['update'], $valid_brands ) ) {
					if ( update_option( 'mm_brand', $assoc_args['update'] ) ) {
						delete_transient( 'mm_icon_hash' );
						WP_CLI::success( 'Plugin branding updated succesfully.' );
					} else {
						WP_CLI::error( 'Unable to update plugin branding.' );
					}
				} else {
					WP_CLI::log( 'Valid brands are : ' . "\n" . implode( "\n", $valid_brands ) );
					WP_CLI::error( 'Invalid update value.' );
				}
			}
		}
		if ( isset( $assoc_args['remove'] ) ) {
			if ( delete_option( 'mm_brand' ) ) {
				delete_transient( 'mm_icon_hash' );
				WP_CLI::success( 'Plugin branding removed succesfully.' );
			}
		}
	}
}