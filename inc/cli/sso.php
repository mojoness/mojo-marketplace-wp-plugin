<?php

/**
 * Class Bluehost_CLI_SSO
 *
 * This class is instantiated in /inc/cli-init.php
 */
class Bluehost_CLI_SSO extends WP_CLI_Command {
	/**
	 * Single Sign On via WP-CLI
	 *
	 * @param  null $args Unused.
	 * @param  array $assoc_args Additional args to define which user or role to login as.
	 */
	public function sso( $args, $assoc_args ) {
		$salt    = wp_generate_password( 32, false );
		$nonce   = wp_create_nonce( 'mojo-sso' );
		$hash    = base64_encode( hash( 'sha256', $nonce . $salt, false ) );
		$hash    = substr( $hash, 0, 64 );
		$minutes = 3;
		$params  = array( 'action' => 'mmsso-check', 'salt' => $salt, 'nonce' => $nonce );

		if ( 0 != count( $assoc_args ) ) {
			if ( isset( $assoc_args['role'] ) ) {
				$user = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
				if ( is_array( $user ) && is_a( $user[0], 'WP_User' ) ) {
					$params['user'] = $user[0]->ID;
				}
			}

			if ( isset( $assoc_args['email'] ) ) {
				$user = get_user_by( 'email', $assoc_args['email'] );
				if ( is_a( $user, 'WP_User' ) ) {
					$params['user'] = $user->ID;
				}
			}

			if ( isset( $assoc_args['username'] ) ) {
				$user = get_user_by( 'login', $assoc_args['username'] );
				if ( is_a( $user, 'WP_User' ) ) {
					$params['user'] = $user->ID;
				}
			}

			if ( isset( $assoc_args['id'] ) ) {
				$user = get_user_by( 'ID', $assoc_args['id'] );
				if ( is_a( $user, 'WP_User' ) ) {
					$params['user'] = $user->ID;
				}
			}

			if ( isset( $assoc_args['min'] ) ) {
				$minutes = (int) $assoc_args['min'];
			}
		}

		set_transient( 'mm_sso', $hash, MINUTE_IN_SECONDS * $minutes );

		$link = add_query_arg( $params, admin_url( 'admin-ajax.php' ) );
		if ( ! isset( $assoc_args['url-only'] ) ) {
			WP_CLI::success( 'Single use login link valid for ' . $minutes . " minutes: \n" . $link );
		} else {
			WP_CLI::log( $link );
		}
	}
}
