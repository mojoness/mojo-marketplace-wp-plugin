<?php

/**
 * Class EIG_WP_CLI_Branding
 *
 * This class is instantiated in /inc/cli-init.php
 */
class EIG_WP_CLI_Branding extends EIG_WP_CLI_Command {

	/**
	 * @var string
	 */
	protected static $option_key = 'mm_brand';

	/**
	 * @var string
	 */
	protected static $icon_transient_key = 'mm_icon_hash';

	/**
	 * @var string
	 */
	protected static $api_branding_file = MM_ASSETS_URL . 'json/branding.json';

	/**
	 * @var string
	 */
	protected $action;

	/**
	 * @var string
	 */
	protected $brand;

	/**
	 * @param $args
	 * @param $assoc_args
	 *
	 * @throws \WP_CLI\ExitException
	 */
	public function __invoke( $args, $assoc_args ) {
		/**
		 * Validate & Decide $this->action + $this->brand
		 */
		if ( isset( $args[0] ) ) {
			if ( 'update' === $args[0] && ! isset( $args[1] ) ) {
				$this->error( 'No brand provided.' );
			}
			$this->action = $args[0];
			$this->brand  = $args[1];
		} elseif ( isset( $assoc_args['update'] ) ) {
			$this->action = 'update';
			$this->brand = $assoc_args['update'];
		} elseif ( isset( $assoc_args['remove'] ) ) {
			$this->action = 'remove';
		} else {
			$this->error( 'No valid action provided.' );
		}

		/**
		 * Route Sub-commands to Methods
		 */
		switch( $this->action ) {
			case 'update':
				$this->update();
				break;
			case 'remove':
				$this->remove();
				break;
		}
	}

	/**
	 * wp {alias} branding update {brand} OR wp {alias} branding --update={brand}
	 *
	 * @throws \WP_CLI\ExitException
	 */
	protected function update() {
		$valid_brands = $this->get_brands();
		$existing_brand = get_option( static::$option_key, null );

		if ( isset( $valid_brands[ $this->brand ] ) ) {
			/**
			 * Check if passed brand param matches saved brand
			 */
			if ( $this->brand === $existing_brand ) {
				$this->success( 'Branding already set to "' . $this->brand . '", wiping transients...');
				delete_transient( static::$icon_transient_key );
				WP_CLI::halt(200);
			} elseif ( update_option( static::$option_key, $this->brand ) ) {
				delete_transient( static::$icon_transient_key );
				$this->success( 'Plugin branding updated to: ' . $this->brand );
			} else {
				$this->error( 'Failed to update plugin branding to: ' . $this->brand );
			}
		} else {
			WP_CLI::log( 'Valid brands are : ' . "\n" . implode( "\n", $valid_brands ) );
			$this->error( 'Didn\'t receive valid brand in subcommand or flag value.' );
		}
	}

	/**
	 * wp {alias} branding remove {brand} OR wp {alias} branding --remove={brand}
	 */
	protected function remove() {
		if ( delete_option( static::$option_key ) ) {
			delete_transient( static::$icon_transient_key );
			$this->success( 'Plugin branding removed successfully.' );
		} else {
			$this->warning( 'No plugin branding found. Try "wp {alias} branding update {brand}"');
			if ( ! empty( $valid_brands = $this->get_brands() ) ) {
				$this->colorize_log( 'Valid brands: ');
				$this->table( $valid_brands, array( '---', 'BRAND SLUG' ) );
			}
		}
	}

	/**
	 *
	 * @return array
	 */
	protected function get_brands() {
		$valid_brands = array();
		$brands = mm_api_cache( static::$api_branding_file );
		if ( ! is_wp_error( $brands ) && $brands = json_decode( $brands['body'] ) ) {
			$brands = (array) $brands;
			while ( false !== ( $brand = array_search( 'default', $brands ) ) ) {
				unset( $brands[ $brand ] );
			}
			$valid_brands = array_keys( $brands );
		}

		return $valid_brands;
	}
}