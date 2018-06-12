<?php

/**
 * Class MOJO_CLI_Module
 */
class MOJO_CLI_Module extends WP_CLI_Command {
	/**
	 * @var array - strings for module options/keys
	 */
	public static $module_options = array(

	);
	/**
	 * Update the appearance of the plugin branding.
	 *
	 * @param  null $args
	 * @param  array $assoc_args
	 */
	public function module( $args, $assoc_args ) {
		switch( $args ) {
			case 'list':
				// do listing
				break;
			case 'enable':
				// do enable
				break;
			case 'disable':
				// do disable
				break;
			case 'status':
				// do status check
				break;
			default:
				WP_CLI::error();
				break;
		}
	}
}
