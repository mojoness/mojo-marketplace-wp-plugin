<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * ## REGISTERING NEW WP-CLI COMMANDS
 *
 * 1. Create new file in /inc/cli.
 * 2. In the file, define a new class extending WP_CLI_Command.
 * 3. Add classname to $mm_cmd_classnames below.
 */

/**
 * Instantiate all command classes. (We can glob b/c order don't matter).
 */
//foreach ( glob( 'cli/*.php' ) as $filename ) {
//	require_once $filename;
//}

include_once 'cli/utilities.php';

// include_once 'cli/branding.php';
// include_once 'cli/cache.php';
include_once 'cli/digest.php';
// include_once 'cli/modules.php';
// include_once 'cli/remove-orphan-post-meta.php';
// include_once 'cli/sso.php';
// include_once 'cli/staging.php';

/**
 * Classnames to make available for WP-CLI.
 */
$mm_cmd_classnames = array(
	// 'Bluehost_CLI_Branding',
	// 'Bluehost_CLI_Cache',
	'Bluehost_CLI_Digest',
	// 'Bluehost_CLI_Remove_Orphan_Post_Meta',
	// 'Bluehost_CLI_SSO',
	// 'Bluehost_CLI_Staging',
	// 'Bluehost_CLI_Module',
);

$mm_cmd_aliases = array(
	'mojo',
	'eig',
	'bluehost',
	'hostmonster',
	'justhost',
	'hostgator',
	'ipage',
	'ipower',
	'fatcow',
	'domain',
	'site5',
);

/**
 * Loop aliases, then loop classnames and add to WP-CLI.
 */
foreach ( $mm_cmd_aliases as $brand ) {
	foreach ( $mm_cmd_classnames as $classname ) {
		WP_CLI::add_command( $brand, $classname );
	}
}
