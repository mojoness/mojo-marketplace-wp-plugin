<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * ## REGISTERING NEW WP-CLI COMMANDS
 *
 * 1. Create new file in /inc/cli.
 * 2. Within the file, define a new class extending EIG_WP_CLI_Command.
 * 3. Add command & classname to $commands array below.
 */
require_once 'cli/abstract-eig-wp-cli-command.php';

foreach ( glob( dirname( __FILE__ ) . '/cli/*.php' ) as $file ) {
	if ( false !== stripos( $file, 'abstract-eig-wp-cli-command' ) ) {
		continue; // already req'd above for order of operations.
	}
	require_once $file;
}

if ( ! defined( 'EIG_WP_CLI_COMMANDS' ) ) {
	define( 'EIG_WP_CLI_COMMANDS', array(
		'branding'                => 'EIG_WP_CLI_Branding',
		'cache'                   => 'EIG_WP_CLI_Cache',
		'digest'                  => 'EIG_WP_CLI_Digest',
		'salts'                   => 'EIG_WP_CLI_Salts',
		'remove_orphan_post_meta' => 'EIG_WP_CLI_Remove_Orphan_Post_Meta',
		'sso'                     => 'EIG_WP_CLI_SSO',
		'staging'                 => 'EIG_WP_CLI_Staging',
		'module'                  => 'EIG_WP_CLI_Module',
	) );
}

$aliases = array(
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
 * Map a brand alias to commands in EIG_WP_CLI_COMMANDS.
 *
 * @param $brand
 *
 * @throws Exception
 */
function eig_wp_cli_register_commands( $brand ) {
	foreach ( EIG_WP_CLI_COMMANDS as $cmd => $classname ) {
		WP_CLI::add_command( $brand . ' ' . $cmd, $classname );
	}
}

/**
 * Map all commands to all aliases
 */
array_map( 'eig_wp_cli_register_commands', $aliases );

