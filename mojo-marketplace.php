<?php
/*
Plugin Name: MOJO Marketplace
Description: This plugin adds shortcodes, widgets, and themes to your WordPress site.
Version: 1.3.4
Author: Mike Hansen
Author URI: http://mikehansen.me?utm_campaign=plugin&utm_source=mojo_wp_plugin
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Do not access file directly!
if ( ! defined( 'WPINC' ) ) { die; }

define( 'MM_BASE_DIR', plugin_dir_path( __FILE__ ) );
define( 'MM_BASE_URL', plugin_dir_url( __FILE__ ) );
define( 'MM_ASSETS_URL', 'https://www.mojomarketplace.com/mojo-plugin-assets/' );
define( 'MM_VERSION', '1.3.4' );

require_once( MM_BASE_DIR . 'inc/base.php' );
require_once( MM_BASE_DIR . 'inc/modules.php' );
require_once( MM_BASE_DIR . 'inc/checkout.php' );
require_once( MM_BASE_DIR . 'inc/menu.php' );
require_once( MM_BASE_DIR . 'inc/shortcode-generator.php' );
require_once( MM_BASE_DIR . 'inc/mojo-themes.php' );
require_once( MM_BASE_DIR . 'inc/styles.php' );
require_once( MM_BASE_DIR . 'inc/plugin-search.php' );
require_once( MM_BASE_DIR . 'inc/jetpack.php' );
require_once( MM_BASE_DIR . 'inc/user-experience-tracking.php' );
require_once( MM_BASE_DIR . 'inc/notifications.php' );
require_once( MM_BASE_DIR . 'inc/spam-prevention.php' );
require_once( MM_BASE_DIR . 'inc/staging.php' );
require_once( MM_BASE_DIR . 'inc/updates.php' );
require_once( MM_BASE_DIR . 'inc/coming-soon.php' );
require_once( MM_BASE_DIR . 'inc/tests.php' );
require_once( MM_BASE_DIR . 'inc/performance.php' );
mm_require( MM_BASE_DIR . 'inc/branding.php' );
mm_require( MM_BASE_DIR . 'inc/sso.php' );
if ( mm_jetpack_bluehost_only() ) {
	$mm_test = get_transient( 'mm_test' );
	if ( isset( $mm_test['name'] ) && false !== strpos( $mm_test['name'], 'jetpack-onboarding' ) ) {
		$onboard_time = strtotime( get_option( 'mm_install_date', 0 ) ) + DAY_IN_SECONDS * 90;
		if ( $onboard_time > time() ) {
			mm_require( MM_BASE_DIR . 'vendor/jetpack/jetpack-onboarding/jetpack-onboarding.php' );
			mm_require( MM_BASE_DIR . 'vendor/jetpack/jetpack-onboarding-tracks/jetpack-onboarding-tracks.php' );
		}
	}
}
mm_require( MM_BASE_DIR . 'updater.php' );
// Check proper PHP and bring CLI loader online
if ( version_compare( PHP_VERSION, '5.3.29' ) >= 0 ) {
	mm_require( MM_BASE_DIR . 'inc/cli-init.php' );
}

