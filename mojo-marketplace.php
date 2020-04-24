<?php
/**
 * Plugin Name: MOJO Marketplace
 * Description: This plugin adds shortcodes, widgets, and themes to your WordPress site.
 * Version: 1.5.2
 * Author: Mike Hansen
 * Author URI: http://mikehansen.me?utm_campaign=plugin&utm_source=mojo_wp_plugin
 * Text Domain: mojo-marketplace-wp-plugin
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Mojo Marketplace
 */

use Endurance_WP_Plugin_Updater\Updater;

// Do not access file directly!
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'MM_VERSION', '1.5.2' );
define( 'MM_BASE_DIR', plugin_dir_path( __FILE__ ) );
define( 'MM_BASE_URL', plugin_dir_url( __FILE__ ) );
define( 'MM_ASSETS_URL', 'https://www.mojomarketplace.com/mojo-plugin-assets/' );

/**
 * Load the plugin translations.
 */
function mojo_marketplace_load_plugin_textdomain() {
	load_plugin_textdomain( 'mojo-marketplace-wp-plugin', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'mojo_marketplace_load_plugin_textdomain' );

// Composer autoloader
require dirname( __FILE__ ) . '/vendor/autoload.php';

require_once MM_BASE_DIR . 'inc/base.php';
require_once MM_BASE_DIR . 'inc/checkout.php';
require_once MM_BASE_DIR . 'inc/menu.php';
require_once MM_BASE_DIR . 'inc/shortcode-generator.php';
require_once MM_BASE_DIR . 'inc/mojo-themes.php';
require_once MM_BASE_DIR . 'inc/styles.php';
require_once MM_BASE_DIR . 'inc/plugin-search.php';
require_once MM_BASE_DIR . 'inc/jetpack.php';
require_once MM_BASE_DIR . 'inc/user-experience-tracking.php';
require_once MM_BASE_DIR . 'inc/notifications.php';
require_once MM_BASE_DIR . 'inc/staging.php';
require_once MM_BASE_DIR . 'inc/updates.php';
require_once MM_BASE_DIR . 'inc/coming-soon.php';
require_once MM_BASE_DIR . 'inc/tests.php';
require_once MM_BASE_DIR . 'inc/track-last-login.php';
require_once MM_BASE_DIR . 'inc/performance.php';
require_once MM_BASE_DIR . 'inc/partners.php';

mm_require( MM_BASE_DIR . 'inc/branding.php' );

// Check proper PHP and bring CLI loader online
if ( version_compare( PHP_VERSION, '5.3.29' ) >= 0 ) {
	mm_require( MM_BASE_DIR . 'inc/cli-init.php' );
}

mm_require( MM_BASE_DIR . 'inc/admin-page-notifications-blocker.php' );

new Updater( 'mojoness', 'mojo-marketplace-wp-plugin', 'mojo-marketplace-wp-plugin/mojo-marketplace.php' );
