<?php

/**
 * Class Bluehost_CLI_Remove_Orphan_Post_Meta
 *
 * This class is instantiated in /inc/cli-init.php
 */
class Bluehost_CLI_Remove_Orphan_Post_Meta extends WP_CLI_Command {
	/**
	 * Remove Orphaned Postmeta.
	 *
	 * @param  null  $args        Unused.
	 * @param  array $assoc_args  Unused.
	 */
	public function remove_orphan_post_meta( $args, $assoc_args ) {
		global $wpdb;
		$sql = 'DELETE pm
			FROM ' . $wpdb->base_prefix . 'postmeta pm
			LEFT JOIN ' . $wpdb->base_prefix . 'posts wp ON wp.ID = pm.post_id
			WHERE wp.ID IS NULL';
		$result = $wpdb->query( $sql );
		if ( false === $result ) {
			WP_CLI::error( 'Unable to remove orphaned postmeta.' );
		} elseif ( 0 === $result ) {
			WP_CLI::log( 'No orphaned postmeta found.' );
		} else {
			WP_CLI::success( 'Successfully removed ' . $result . ' orphaned postmeta records.' );
		}
	}
}
