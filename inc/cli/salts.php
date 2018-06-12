<?php

/**
 * Class Bluehost_CLI_Digest
 *
 * This class is instantiated in /inc/cli-init.php
 */
class Bluehost_CLI_Salts extends EIG_WP_CLI_Command {
	/**
	 * @var array
	 */
	public static $salt_slugs   = array(
		'AUTH_KEY',
		'SECURE_AUTH_KEY',
		'LOGGED_IN_KEY',
		'NONCE_KEY',
		'AUTH_SALT',
		'SECURE_AUTH_SALT',
		'LOGGED_IN_SALT',
		'NONCE_SALT',
	);

	private $built_salts = array();

	/**
	 * @param $args
	 * @param $assoc_args
	 */
	public function salts( $args, $assoc_args ) {
		if ( isset( $args[0] ) ) {
			switch( $args[0] ) {
				case 'update':
					$this->update();
					break;
				default:
					$this->info( 'No default command for salts, try salts update.' );
					break;
			}
		} else {
			$this->info( 'No default command for salts, try salts update.' );
		}
	}

	/**
	 * Update wp-config.php salts using WP_CLI & wp_generate_password()
	 */
	protected function update() {
		$this->confirm( 'Updating the salts logs any active users out. Do it?', 'yellow' );
		$this->build_salts();
		if ( ! empty( $this->built_salts ) ) {
			foreach ( $this->built_salts as $salt_key => $salt_value ) {
				WP_CLI::run_command(
					array(
						'config',
						'set',
						$salt_key,
						"$salt_value",
					),
					array(
						'type' => 'constant',
					)
				);
			}
		}
	}

	/**
	 * Use wp_generate_password() to create 64 character randomized strings with "extra" special characters
	 */
	protected function build_salts() {
		foreach( static::$salt_slugs as $salt_slug ) {
			$this->built_salts[ $salt_slug ] = wp_generate_password( 64, true, true );
		}
	}
}