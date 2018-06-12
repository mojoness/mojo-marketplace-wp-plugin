<?php

/**
 * Class EIG_WP_CLI_Salts
 *
 * This class is instantiated in /inc/cli-init.php
 */
class EIG_WP_CLI_Salts extends EIG_WP_CLI_Command {
	/**
	 * @var array
	 */
	protected static $salt_slugs   = array(
		'AUTH_KEY',
		'SECURE_AUTH_KEY',
		'LOGGED_IN_KEY',
		'NONCE_KEY',
		'AUTH_SALT',
		'SECURE_AUTH_SALT',
		'LOGGED_IN_SALT',
		'NONCE_SALT',
	);

	/**
	 * @var array
	 */
	protected $built_salts = array();

	/**
	 * @var string
	 */
	protected static $salt_timestamp_key = 'eig_generated_keys_salts_timestamp';

	/**
	 * @var string
	 */
	protected $subcommand;

	/**
	 * @param $args
	 * @param $assoc_args
	 * @throws \WP_CLI\ExitException
	 */
	public function __invoke( $args, $assoc_args ) {
		$this->subcommand = isset( $args[0] ) ? $args[0] : 'list';
		switch( $this->subcommand ) {
			case 'update':
				$this->update();
				break;
			case 'timestamp':
				$this->timestamp();
				break;
			case 'list':
			default:
				$this->list_salts();
				break;
		}
	}

	/**
	 * Run 'wp config list'
	 */
	protected function list_salts() {
		$fields = implode( ' ', static::$salt_slugs );
		\WP_CLI::runcommand( 'config list ' . $fields . ' --strict' );
		if ( ! empty( $db_time = get_option( static::$salt_timestamp_key, null ) ) && is_string( $db_time ) ) {
			$this->info( 'Salts last generated ' . human_time_diff( strtotime( $db_time ), current_time('timestamp' ) ) . ' ago.' );
		} else {
			$this->warning('Salts were created on WordPress install/manually and haven\'t been regenerated.' );
		}
	}

	/**
	 * Update wp-config.php _KEY and _SALT constants using wp config set.
	 * @throws \WP_CLI\ExitException
	 */
	protected function update() {
		$this->confirm( 'Updating the salts logs any active users out. Do it?', 'yellow' );
		/**
		 * Create new salts using wp_generate_password( 64, true true ) for 64-character alphanum + special keys.
		 */
		$this->build_salts();
		if ( ! empty( $this->built_salts ) ) {
			// delete existing generated constant
			delete_option( static::$salt_timestamp_key );
			foreach ( $this->built_salts as $salt_key => $salt_value ) {
				\WP_CLI::run_command(
					array(
						'config',
						'set',
						$salt_key,
						"$salt_value",
					),
					array(
						'type' => 'constant',
						'anchor' => '* @since 2.6.0.'.PHP_EOL.'*/',
						'placement' => 'after',
					)
				);
			}
			add_option( static::$salt_timestamp_key, current_time('mysql'), '', false );
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

	/**
	 *
	 */
	protected function timestamp() {
		$stored_timestamp = get_option( static::$salt_timestamp_key, null );
		if ( ! empty( $stored_timestamp ) ) {
			$this->colorize_log();
		}
	}
}