<?php

/**
 * Customize the default Jetpack modules that should be enabled.
 *
 * @param array $modules List of default enabled Jetpack modules
 *
 * @return array
 */
function mm_customize_jetpack_default_modules( $modules ) {
	$modules[] = 'photon';
	$modules[] = 'sso';

	return array_unique( $modules );
}

add_filter( 'jetpack_get_default_modules', 'mm_customize_jetpack_default_modules' );

/**
 * Unregister the MailChimp block.
 *
 * @param array $blocks Collection of registered blocks.
 *
 * @return array
 */
function mm_jetpack_unregister_blocks( $blocks ) {
	$blocks_to_deregister = array(
		'mailchimp',
		'revue',
	);
	foreach ( $blocks_to_deregister as $block_slug ) {
		$found = array_search( $block_slug, $blocks, true );
		if ( false !== $found ) {
			unset( $blocks[ $found ] );
		}
	}
	return $blocks;
}
add_filter( 'jetpack_set_available_blocks', 'mm_jetpack_unregister_blocks' );

add_filter( 'jetpack_show_setup_wizard', '__return_true' );
add_filter( 'jetpack_pre_connection_prompt_helpers', '__return_true' );
