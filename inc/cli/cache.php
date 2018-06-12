<?php

/**
 * Class Bluehost_CLI_Cache
 *
 * This class is instantiated in /inc/cli-init.php
 */
class Bluehost_CLI_Cache extends WP_CLI_Command {

	/**
	 * @var string
	 */
	public static $org_url = 'https://raw.githubusercontent.com/bluehost';

	/**
	 * @var array - Types of caching available.
	 */
	public static $cache_types = array(
		'page',
		'browser',
		'object',
	);

	/**
	 * @var array - Actions taken with all caching types.
	 */
	public static $cache_actions = array(
		'add',
		'remove',
	);

	/**
	 * @var string
	 */
	public static $page_repo_branch = 'endurance-page-cache/production';

	/**
	 * @var string filename
	 */
	public static $page_filename = 'endurance-page-cmd-cache.php';

	/**
	 * @var string - repo-slug/branch/root_dir(file)
	 */
	public static $browser_repo_branch = 'endurance-browser-cache/production';

	/**
	 * @var string filename
	 */
	public static $browser_filename = 'endurance-browser-cmd-cache.php';

	/**
	 * @var string|null
	 */
	public $current_action;

	/**
	 * @var string|null
	 */
	public $current_type;

	/**
	 *
	 */

	/**
	 * Enable or disable page, browser or object caching.
	 *
	 * Either retrieve the appropriate plugin file from GitHub or remove file from mu-plugin directory.
	 *
	 * @param  array $args Action and Type.
	 * @param  array $assoc_args Unused.
	 */
	public function cache( $args, $assoc_args ) {

		if ( ! is_array( $args ) || ! isset( $args[0] ) ) {
			WP_CLI::error( 'No action provided.' );
		}
		if ( ! in_array( $args[0], static::$cache_actions ) ) {
			WP_CLI::error( 'Invalid action. Use: add or remove.' );
		}

		$this->current_action = $args[0];

		if ( ! is_array( $args ) || ! isset( $args[1] ) ) {
			WP_CLI::error( 'No cache type selected.' );
		}

		if ( ! in_array( $args[1], static::$cache_types ) ) {
			WP_CLI::error( 'Invalid cache type.' );
		}

		$this->current_type = $args[1];

		if ( 'add' == $this->current_action ) {
			switch ( $this->current_type ) {
				case 'page':
					$this->maybe_make_mu_plugin_dir();
					$this->get_plugin_from_githubraw(
						$this->current_type,
						trailingslashit( static::$org_url ) . trailingslashit( static::$page_repo_branch ) . static::$page_filename,
						static::$page_filename
					);
					break;
				case 'browser':
					$this->maybe_make_mu_plugin_dir();
					$this->get_plugin_from_githubraw(
						$this->current_type,
						trailingslashit( static::$org_url ) . trailingslashit( static::$browser_repo_branch ) . static::$browser_filename,
						static::$browser_filename
					);
					break;
				case 'object':
					WP_CLI::log( 'Object cache coming soon!' );
				default:
					WP_CLI::log( '' );
					break;
			}
		} else if ( 'remove' == $args[0] ) {
			switch ( $args[1] ) {
				case 'page':
					if ( file_exists( WP_CONTENT_DIR . '/mu-plugins/endurance-page-cmd-cache.php' ) ) {
						if ( unlink( WP_CONTENT_DIR . '/mu-plugins/endurance-page-cmd-cache.php' ) ) {
							save_mod_rewrite_rules();
							WP_CLI::success( 'Page caching disabled.' );
						} else {
							WP_CLI::error( 'Unable to remove page cache file from mu-plugins directory.' );
						}
					} else {
						WP_CLI::log( 'Page caching plugin file does not exist.' );
					}
					break;

				case 'browser':
					if ( file_exists( WP_CONTENT_DIR . '/mu-plugins/endurance-browser-cmd-cache.php' ) ) {
						if ( unlink( WP_CONTENT_DIR . '/mu-plugins/endurance-browser-cmd-cache.php' ) ) {
							save_mod_rewrite_rules();
							WP_CLI::success( 'Browser caching disabled.' );
						} else {
							WP_CLI::error( 'Unable to remove browser cache file from mu-plugins directory.' );
						}
					} else {
						WP_CLI::log( 'Browser caching plugin file does not exist.' );
					}
					break;

				case 'browser':
					WP_CLI::log( 'Object cache coming soon!' );
					break;
			}
		}
	}

	/**
	 * Use WordPress HTTP Library to Retrieve Single-File Dropin Plugin from Public GitHub Repository
	 *
	 * @param $cache
	 * @param $url
	 * @param $filename
	 * @param string $dir
	 */
	private function get_plugin_from_githubraw( $cache, $url, $filename, $dir = WP_CONTENT_DIR . '/mu-plugins' ) {
		$response = wp_remote_get( $url );
		if (
			 ! is_wp_error( $response )
		     && is_array( $response )
		     && isset( $response['body'] )
		     && strlen( $response['body'] ) > 200
			)
		{
			file_put_contents( trailingslashit( $dir ) . $filename, $response['body'] );
			if ( file_exists( WP_CONTENT_DIR . '/mu-plugins/endurance-page-cmd-cache.php' ) ) {
				save_mod_rewrite_rules();
				WP_CLI::success( $cache . ' cache enabled.' );
			}
		} else {
			WP_CLI::error( 'Error activating ' . $cache .' cache.' );
		}
	}

	private function maybe_make_mu_plugin_dir() {
		$mu_plugin_dir = WP_CONTENT_DIR . '/mu-plugins';
		$tried_making_dir = false;
		if ( is_dir( $mu_plugin_dir ) ) {
			WP_CLI::success( 'Found /wp-content/mu-plugins.' );
		} else {
			$tried_making_dir = true;
			mkdir( $mu_plugin_dir );
			WP_CLI::log( 'Creating mu-plugins directory...' );;
		}

		if ( $tried_making_dir && is_dir( $mu_plugin_dir )  ) {
			WP_CLI::success( 'Created /wp-content/mu-plugins.' );
		} else {
			WP_CLI::error( 'Failed to create /wp-content/mu-plugins.' );
		}
	}
}