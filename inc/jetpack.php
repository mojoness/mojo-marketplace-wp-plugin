<?php
// Add Photon to the list of default modules activated when Jetpack is linked to WP.
function mm_customize_jetpack_default_modules( $modules ) {
	$modules[] = 'photon';
	$modules[] = 'sso';
	return array_unique( $modules );
}
add_filter( 'jetpack_get_default_modules', 'mm_customize_jetpack_default_modules' );

function mm_jetpack_unregister_mailchimp_block( $blocks ) {
    $found = array_search( 'mailchimp', $blocks );
    if ( false !== $found ) {
        unset( $blocks[ $found ] );
    }
    return $blocks;
}
add_filter( 'jetpack_set_available_blocks', 'mm_jetpack_unregister_mailchimp_block' );

function mm_jpo_from( $from, $version ) {
	if ( mm_brand() == 'bluehost' ) {
		return sprintf( 'jpo-%s-bluehost', $version );
	} else {
		return $from;
	}
}
add_filter( 'jpo_tracking_from_arg', 'mm_jpo_from', 10, 2 );
