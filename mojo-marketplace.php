<?php
/**
 * Plugin Name: MOJO Marketplace
 * Description: This plugin adds shortcodes, widgets, and themes to your WordPress site.
 * Version: 1.5.6
 * Author: Mike Hansen
 * Author URI: http://mikehansen.me?utm_campaign=plugin&utm_source=mojo_wp_plugin
 * Requires at least: 4.7
 * Requires PHP: 5.3
 * Text Domain: mojo-marketplace-wp-plugin
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package MojoMarketplace
 */

// Do not access file directly!
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'MM_VERSION', '1.5.6' );
define( 'MM_BASE_DIR', plugin_dir_path( __FILE__ ) );
define( 'MM_BASE_URL', plugin_dir_url( __FILE__ ) );
define( 'MM_ASSETS_URL', 'https://www.mojomarketplace.com/mojo-plugin-assets/' );

global $pagenow;
if ( 'plugins.php' === $pagenow ) {

	require dirname( __FILE__ ) . '/inc/plugin-php-compat-check.php';

	$plugin_check = new Mojo_Plugin_PHP_Compat_Check( __FILE__ );

	$plugin_check->min_php_version = '5.3';
	$plugin_check->min_wp_version  = '4.7';

	$plugin_check->check_plugin_requirements();
}

// Check PHP version before initializing to prevent errors if plugin is incompatible.
if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
	require dirname( __FILE__ ) . '/bootstrap.php';
}
