<?php

use \WP_CLI\Utils;

/**
 * Class EIG_WP_CLI_Cache
 *
 * This class is instantiated in /inc/cli-init.php
 */
class EIG_WP_CLI_Cache extends EIG_WP_CLI_Command {

	/**
	 * @var string - Organization Raw Content URL Base
	 */
	protected static $org_url = 'https://raw.githubusercontent.com/bluehost';

	/**
	 * @var array - Types of caching available.
	 */
	protected static $cache_types = array(
		'page',
		'browser',
		'object',
	);

	/**
	 * @var array - Actions taken with all caching types.
	 */
	protected static $cache_actions = array(
		'add',
		'remove',
		'status',
	);

	/**
	 * @var string
	 */
	protected static $page_repo_branch = 'endurance-page-cache/production';

	/**
	 * @var string
	 */
	protected static $page_filename = 'endurance-page-cache.php';

	/**
	 * @var string
	 */
	protected static $browser_repo_branch = 'endurance-browser-cache/production';

	/**
	 * @var string
	 */
	protected static $browser_filename = 'endurance-browser-cache.php';

	/**
	 * @var string
	 */
	protected static $mu_plugin_dir = WP_CONTENT_DIR . '/mu-plugins';

	/**
	 * @var string|null
	 */
	protected $current_action;

	/**
	 * @var string|null
	 */
	protected $current_type;

	/**
	 * EIG_WP_CLI_Cache constructor.
	 */
	public function __invoke( $args, $assoc_args ) {
		if ( ! is_array( $args ) || ! isset( $args[0] ) || ! isset( $args[1] ) ) {
			$this->error( 'Arguments weren\'t an array() or didn\'t have first two params set' );
			WP_CLI::halt( 400 );
		}

		$this->current_type = $args[0];
		$this->current_action = $args[1];

		if ( ! in_array( $this->current_type, static::$cache_types ) ) {
			$this->error( 'Cache type bad yo' );
			WP_CLI::halt( 400 );

		}

		if ( ! in_array( $this->current_action, static::$cache_actions ) ) {
			$this->error( 'Cache action bad yo' );
			WP_CLI::halt( 400 );
		}

		if ( 'add' === $this->current_action ) {
			$this->add();
		} else if ( 'remove' === $this->current_action ) {
			$this->remove();
		}
	}

	/**
	 *
	 */
	private function add() {
		switch ( $this->current_type ) {
			case 'page':
				$this->maybe_make_mu_plugin_dir();
				if ( file_exists( trailingslashit( static::$mu_plugin_dir ) . static::$page_filename ) ) {
					$this->confirm( 'Plugin already exists. Replace with fresh copy?', 'underline'
					);
				}
				$this->get_plugin_from_githubraw(
					$this->build_url( static::$org_url, static::$page_repo_branch, static::$page_filename ),
					static::$page_filename
				);
				break;
			case 'browser':
				$this->maybe_make_mu_plugin_dir();
				if ( file_exists( trailingslashit( static::$mu_plugin_dir ) . static::$browser_filename ) ) {
					$this->confirm( 'Plugin already exists. Replace with fresh copy?', 'underline' );
				}
				$this->get_plugin_from_githubraw(
					$this->build_url( static::$org_url, static::$browser_repo_branch, static::$browser_filename ),
					static::$browser_filename
				);
				break;
			case 'object':
				$this->colorize_log( 'Object caching isn\'t available right now.' );
				break;
		}
	}

	/**
	 *
	 */
	private function remove() {
		switch ( $this->current_type ) {
			case 'page':
				$file = Utils\trailingslashit( static::$mu_plugin_dir ) . static::$page_filename;
				$this->remove_mu_plugin( $file );
				break;
			case 'browser':
				$file = Utils\trailingslashit( static::$mu_plugin_dir ) . static::$browser_filename;
				$this->remove_mu_plugin( $file );
				break;
			case 'object':
				$this->colorize_log( 'Object caching isn\'t available right now.' );
				break;
		}
	}

	/**
	 * Use WordPress HTTP Library to Retrieve Single-File Drop-In Plugin from GitHub Repository
	 *
	 * @param $url
	 * @param $filename
	 * @param string $dir
	 * @throws \WP_CLI\ExitException
	 */
	private function get_plugin_from_githubraw( $url, $filename, $dir = '' ) {
		$this->colorize_log( 'Downloading ' . ucfirst( $this->current_type ) . ' Cache from GitHub...' );
		$dir = ! empty( $dir ) ? Utils\trailingslashit( $dir ) : Utils\trailingslashit( static::$mu_plugin_dir );
		$response = Utils\http_request( 'GET', $url );
		if (
			 is_object( $response )
			 && isset( $response->status_code )
			 && isset( $response->body )
			 && 200 === $response->status_code
		) {
			$this->colorize_log( 'Adding timestamp to file...' );
			$file_contents = $response->body . '/**' . PHP_EOL . '* FILE CREATED VIA WP-CLI' . PHP_EOL .'* ' . current_time('mysql' ) . PHP_EOL . '*/' . PHP_EOL;
			file_put_contents( $dir . $filename, $file_contents );
			if ( file_exists( $dir . $filename ) ) {
				save_mod_rewrite_rules();
				$this->success( ucfirst( $this->current_type ) . ' Cache placed in /mu-plugins/'. $filename .'. It\'s active!' );
			} else {
				$this->error( 'Couldn\'t write ' . $this->current_type .' cache file to ' . $dir );
			}
		} else {
			$this->error( 'Couldn\'t download ' . $this->current_type .' cache from ' . $url );
		}
	}

	/**
	 *
	 */
	private function maybe_make_mu_plugin_dir() {
		$mu_plugin_dir = WP_CONTENT_DIR . '/mu-plugins';
		$tried_making_dir = false;
		if ( is_dir( $mu_plugin_dir ) ) {
			$this->colorize_log( 'Found ' . static::$mu_plugin_dir, '', 'G' );
		} else {
			$tried_making_dir = true;
			mkdir( $mu_plugin_dir );
			$this->colorize_log( 'Creating ' . static::$mu_plugin_dir );
		}

		if ( $tried_making_dir && is_dir( $mu_plugin_dir )  ) {
			$this->success( 'Created ' . static::$mu_plugin_dir );
		} elseif ( $tried_making_dir ) {
			$this->error( 'Failed to create ' . static::$mu_plugin_dir . '. Update write permissions and try again.' );
		}
	}

	/**
	 * Check for a mu-plugin and if file exists, remove it.
	 */
	private function remove_mu_plugin( $file ) {
		if ( file_exists( $file ) ) {
			if ( unlink( $file ) ) {
				save_mod_rewrite_rules();
				$this->success( ucfirst( $this->current_type ) . ' caching disabled.' );
			} else {
				$this->error( 'Failed to remove ' . ucfirst( $this->current_type ) . ' cache file from ' . static::$mu_plugin_dir );
			}
		} else {
			$this->warning( ucfirst( $this->current_type ) . ' caching plugin file does not exist.' );
		}
	}

	/**
	 * Simple URL construction method from class constants
	 *
	 * @param $root
	 * @param $repo_branch
	 * @param $filename
	 *
	 * @return string
	 */
	private function build_url( $root, $repo_branch, $filename ) {
		return Utils\trailingslashit( $root ) . Utils\trailingslashit( $repo_branch ) . $filename;
	}
}
