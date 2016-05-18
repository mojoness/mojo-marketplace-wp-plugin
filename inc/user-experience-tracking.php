<?php
/*
This file tracks basic user actions to improve the user experience.
*/

function mm_ux_log( $args = array() ) {
	$url = 'https://ssl.google-analytics.com/collect';

	global $title;

	if ( empty( $_SERVER['REQUEST_URI'] ) ) {
		return;
	}

	$path = explode( 'wp-admin', $_SERVER['REQUEST_URI'] );

	if ( empty( $path ) || empty( $path[1] ) ) {
		$path = array( '', ' ' );
	}

	$defaults = array(
		'v'		=> '1',
		'tid'	=> 'UA-39246514-3',
		't'		=> 'pageview',
		'cid'	=> md5( get_option( 'siteurl' ) ),
		'uid'	=> md5( get_option( 'siteurl' ) . get_current_user_id() ),
		'cn'	=> 'mojo_wp_plugin',
		'cs'	=> 'mojo_wp_plugin',
		'cm'	=> 'plugin_admin',
		'ul'	=> get_locale(),
		'dp'	=> $path[1],
		'sc'	=> '',
		'ua'	=> ( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '',
		'dl'	=> $path[1],
		'dh'	=> get_option( 'siteurl' ),
		'dt'	=> $title,
		'ec'	=> '',
		'ea'	=> '',
		'el'	=> '',
		'ev'	=> '',
		'cd1'   => ( defined( 'MM_VERSION' ) ) ? MM_VERSION : '',
		'cd2'   => mm_brand(),
		'cd3'   => get_option( 'mm_host', '' ),
	);

	if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		$defaults['uip'] = $_SERVER['REMOTE_ADDR'];
	}

	$params = wp_parse_args( $args, $defaults );

	$test = get_transient( 'mm_test', '' );

	if ( isset( $test['key'] ) && isset( $test['name'] ) ) {
		$params['cm'] = $params['cm'] . '_' . $test['name'] . '_' . $test['key'];
	}

	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		$params['tid'] = 'UA-19617272-27';
	}

	$z = wp_rand( 0, 1000000000 );

	$query = http_build_query( array_filter( $params ) );

	$args = array(
		'body'		=> $query,
		'method'	=> 'POST',
		'blocking'	=> false,
		'timeout'   => 0.1,
	);

	$url = add_query_arg( array( 'z' => $z ), $url );
	wp_remote_post( $url, $args );
	do_action( 'mm_ux_log', $params, $args, $url );
}
add_action( 'admin_footer', 'mm_ux_log', 9 );
add_action( 'customize_controls_print_footer_scripts', 'mm_ux_log' );

function mm_ux_log_start() {
	$session = array(
		'sc' => 'start',
	);
	mm_ux_log( $session );
	$event = array(
		't'  => 'event',
		'ec' => 'user_action',
		'ea' => 'login',
	);
	mm_ux_log( $event );
}
add_action( 'wp_login', 'mm_ux_log_start' );

function mm_ux_log_end() {
	$session = array(
		'sc' => 'end',
	);
	mm_ux_log( $session );
	$user = get_user_by( 'id', get_current_user_id() );
	$role = $user->roles;
	$event = array(
		't'  => 'event',
		'ec' => 'user_action',
		'ea' => 'logout',
		'el' => $role[0],
	);
	mm_ux_log( $event );
}
add_action( 'wp_logout', 'mm_ux_log_end' );

function mm_ux_log_deactivated() {
	$event = array(
		't'		=> 'event',
		'ec'	=> 'plugin_status',
		'ea'	=> 'deactivated',
		'el'	=> 'Install date: ' . get_option( 'mm_install_date', date( 'M d, Y' ) ),
	);
	mm_ux_log( $event );
}

function mm_ux_log_activated() {
	$event = array(
		't'		=> 'event',
		'ec'	=> 'plugin_status',
		'ea'	=> 'activated',
		'el'	=> 'Install date: ' . get_option( 'mm_install_date', date( 'M d, Y' ) ),
	);
	mm_ux_log( $event );
}
register_activation_hook( MM_BASE_DIR . "mojo-marketplace.php", 'mm_ux_log_activated' );
register_deactivation_hook( MM_BASE_DIR . "mojo-marketplace.php", 'mm_ux_log_deactivated' );

function mm_ux_log_theme_preview() {
	global $theme;
	if ( isset( $_GET['page'] ) && 'mojo-theme-preview' == $_GET['page'] && ! is_wp_error( $theme ) ) {
		$event = array(
			't'		=> 'event',
			'ec'	=> 'theme_preview',
			'ea'	=> esc_attr( $_GET['items'] ),
			'el'	=> $theme->name,
		);
		mm_ux_log( $event );
	}
}
add_action( 'admin_footer', 'mm_ux_log_theme_preview' );

function mm_ux_log_theme_category_org() {
	if ( isset( $_GET['browse'] ) ) {
		$category = esc_attr( $_GET['browse'] );
	} else {
		$category = 'featured';
	}
	$event = array(
		't'		=> 'event',
		'ec'	=> 'theme_category',
		'ea'	=> 'org',
		'el'	=> $category,
	);
	mm_ux_log( $event );
}
add_action( 'admin_footer-theme-install.php', 'mm_ux_log_theme_category_org' );

function mm_ux_log_theme_category_mojo() {
	if ( isset( $_GET['page'] ) && 'mojo-themes' == $_GET['page'] ) {
		if ( isset( $_GET['items'] ) ) {
			$category = esc_attr( $_GET['items'] );
		} else {
			$category = 'popular';
		}

		$event = array(
			't'		=> 'event',
			'ec'	=> 'theme_category',
			'ea'	=> 'mojo',
			'el'	=> $category,
		);
		mm_ux_log( $event );
	}
}
add_action( 'admin_footer', 'mm_ux_log_theme_category_mojo' );

function mm_ux_log_plugin_version() {
	$plugin = get_plugin_data( MM_BASE_DIR . 'mojo-marketplace.php' );
	$event = array(
		't'		=> 'event',
		'ec'	=> 'scheduled',
		'ea'	=> 'plugin_version',
		'el'	=> $plugin['Version'],
	);
	$events = get_option( 'mm_cron', array() );
	$events['daily'][ $event['ea'] ] = $event;
	update_option( 'mm_cron', $events );
}
add_action( 'admin_footer-index.php', 'mm_ux_log_plugin_version' );

function mm_ux_log_php_version() {
	$event = array(
		't'		=> 'event',
		'ec'	=> 'scheduled',
		'ea'	=> 'php_version',
		'el'	=> phpversion(),
	);
	$events = get_option( 'mm_cron', array() );
	$events['monthly'][ $event['ea'] ] = $event;
	update_option( 'mm_cron', $events );
}
add_action( 'admin_footer-index.php', 'mm_ux_log_php_version' );

function mm_ux_log_wp_version() {
	global $wp_version;
	$event = array(
		't'		=> 'event',
		'ec'	=> 'scheduled',
		'ea'	=> 'wp_version',
		'el'	=> $wp_version,
	);
	$events = get_option( 'mm_cron', array() );
	$events['weekly'][ $event['ea'] ] = $event;
	update_option( 'mm_cron', $events );
}
add_action( 'admin_footer-index.php', 'mm_ux_log_wp_version' );

function mm_ux_log_plugin_count() {
	$plugins = get_option( 'active_plugins' );
	$event = array(
		't'		=> 'event',
		'ec'	=> 'scheduled',
		'ea'	=> 'plugin_count',
		'el'	=> count( $plugins ),
	);
	$events = get_option( 'mm_cron', array() );
	$events['monthly'][$event['ea']] = $event;
	update_option( 'mm_cron', $events );
}
add_action( 'admin_footer-index.php', 'mm_ux_log_plugin_count' );

function mm_ux_log_theme_count() {
	$theme_root = get_theme_root();
	$files = glob( $theme_root . '/*' );
	$count = 0;
	foreach ( $files as $file ) {
		if ( is_dir( $file ) ) {
			$count++;
		}
	}
	$event = array(
		't'		=> 'event',
		'ec'	=> 'scheduled',
		'ea'	=> 'theme_count',
		'el'	=> $count,
	);
	$events = get_option( 'mm_cron', array() );
	$events['monthly'][$event['ea']] = $event;
	update_option( 'mm_cron', $events );
}
add_action( 'admin_footer-index.php', 'mm_ux_log_theme_count' );

function mm_ux_log_current_theme() {
	$theme = get_option( 'stylesheet' );
	$event = array(
		't'		=> 'event',
		'ec'	=> 'scheduled',
		'ea'	=> 'current_theme',
		'el'	=> $theme,
	);
	$events = get_option( 'mm_cron', array() );
	$events['monthly'][ $event['ea'] ] = $event;
	update_option( 'mm_cron', $events );
}
add_action( 'admin_footer-index.php', 'mm_ux_log_current_theme' );

function mm_ux_log_scheduled_events_weekly() {
	$events = get_option( 'mm_cron', array( 'weekly' => array() ) );
	if ( isset( $events['weekly'] ) && count( $events['weekly'] ) >= 1 ) {
		$weekly_events = $events['weekly'];
		foreach ( $weekly_events as $event => $details ) {
			if ( isset( $details['keep'] ) ) {
				if ( false === $details['keep'] ) {
					unset( $weekly_events[ $event ] );
				}
				unset( $details['keep'] );
			}
			mm_ux_log( $details );
		}
	}
	$events['weekly'] = $weekly_events;
	update_option( 'mm_cron', $events );
}
add_action( 'mm_cron_weekly', 'mm_ux_log_scheduled_events_weekly' );

function mm_ux_log_scheduled_events_monthly() {
	$events = get_option( 'mm_cron', array( 'monthly' => array() ) );
	if ( isset( $events['monthly'] ) && count( $events['monthly'] ) >= 1 ) {
		$monthly_events = $events['monthly'];
		foreach ( $monthly_events as $event => $details ) {
			if ( isset( $details['keep'] ) ) {
				if ( false === $details['keep'] ) {
					unset( $monthly_events[ $event ] );
				}
				unset( $details['keep'] );
			}
			mm_ux_log( $details );
		}
		$events['monthly'] = $monthly_events;
		update_option( 'mm_cron', $events );
	}
}
add_action( 'mm_cron_monthly', 'mm_ux_log_scheduled_events_monthly' );

function mm_ux_log_scheduled_events_twicedaily() {
	$events = get_option( 'mm_cron', array( 'twicedaily' => array() ) );
	if ( isset( $events['twicedaily'] ) && count( $events['twicedaily'] ) >= 1 ) {
		$twicedaily_events = $events['twicedaily'];
		foreach ( $twicedaily_events as $event => $details ) {
			if ( isset( $details['keep'] ) ) {
				if ( false === $details['keep'] ) {
					unset( $twicedaily_events[ $event ] );
				}
				unset( $details['keep'] );
			}
			mm_ux_log( $details );
		}
		$events['twicedaily'] = $twicedaily_events;
		update_option( 'mm_cron', $events );
	}
}
add_action( 'mm_cron_twicedaily', 'mm_ux_log_scheduled_events_twicedaily' );

function mm_ux_log_scheduled_events_daily() {
	$events = get_option( 'mm_cron', array( 'daily' => array() ) );
	if ( isset( $events['daily'] ) && count( $events['daily'] ) >= 1 ) {
		$daily_events = $events['daily'];
		foreach ( $daily_events as $event => $details ) {
			if ( isset( $details['keep'] ) ) {
				if ( false === $details['keep'] ) {
					unset( $daily_events[ $event ] );
				}
				unset( $details['keep'] );
			}
			mm_ux_log( $details );
		}
		$events['daily'] = $daily_events;
		update_option( 'mm_cron', $events );
	}
}
add_action( 'mm_cron_daily', 'mm_ux_log_scheduled_events_daily' );

function mm_ux_log_scheduled_events_hourly() {
	$events = get_option( 'mm_cron', array( 'hourly' => array() ) );
	if ( isset( $events['hourly'] ) && count( $events['hourly'] ) >= 1 ) {
		$hourly_events = $events['hourly'];
		foreach ( $hourly_events as $event => $details ) {
			if ( isset( $details['keep'] ) ) {
				if ( false === $details['keep'] ) {
					unset( $hourly_events[ $event ] );
				}
				unset( $details['keep'] );
			}
			mm_ux_log( $details );
		}
		$events['hourly'] = $hourly_events;
		update_option( 'mm_cron', $events );
	}
}
add_action( 'mm_cron_hourly', 'mm_ux_log_scheduled_events_hourly' );

function mm_ux_log_plugin_search() {
	if ( isset( $_GET['tab'] ) && isset( $_GET['s'] ) ) {
		$event = array(
			't'		=> 'event',
			'ec'	=> 'user_action',
			'ea'	=> 'plugin_search',
			'el'	=> esc_attr( $_GET['s'] ),
		);
		mm_ux_log( $event );
	}
}
add_action( 'admin_footer-plugin-install.php', 'mm_ux_log_plugin_search' );

function mm_ux_log_content_status( $new_status, $old_status, $post ) {
	$status = array( 'draft', 'pending', 'publish', 'new', 'future', 'private', 'trash' );
	if ( $old_status !== $new_status && in_array( $new_status, $status ) ) {
		$event = array(
			't'     => 'event',
			'ec'    => 'user_action',
			'ea'    => 'content_status',
			'el'    => $new_status,
		);
		mm_ux_log( $event );
	}
	//first post is 3 because of the example post and page.
	if ( 3 == $post->ID ) {
		$event = array(
			't'     => 'event',
			'ec'    => 'user_action',
			'ea'    => 'first_post',
			'el'    => $post->post_type,
		);
		mm_ux_log( $event );
	}

	//fifth post is 7 because of the example post and page.
	if( $post->ID == 7 ) {
		$event = array(
			't'     => 'event',
			'ec'    => 'user_action',
			'ea'    => 'fifth_post',
			'el'    => $post->post_type,
		);
		mm_ux_log( $event );
	}
}
add_action( 'transition_post_status', 'mm_ux_log_content_status', 10, 3 );

function mm_ux_log_comment_status( $new_status, $old_status, $comment ) {
	$status = array( 'deleted', 'approved', 'unapproved', 'spam' );
	if ( $old_status !== $new_status && in_array( $new_status, $status ) ) {
		$event = array(
			't'		=> 'event',
			'ec'	=> 'user_action',
			'ea'	=> 'comment_status',
			'el'	=> $new_status,
		);
		mm_ux_log( $event );
	}
}
add_action( 'transition_comment_status', 'mm_ux_log_comment_status', 10, 3 );

function mm_ux_log_buy_now_clicks_preview() {
	if ( isset( $_GET['page'] ) && false !== strpos( $_GET['page'], 'mojo-' ) ) {
		global $theme;
		?>
		<script type="text/javascript">
			jQuery( '.btn.btn-success' ).click( function( $ ) {
				var view = $( this ).data( 'view' );
				var value = $( this ).data( 'price' );
				var nonce = "<?php echo wp_create_nonce( 'mm_nonce-buy_now_click' ); ?>";
				$.ajax( ajaxurl + "?action=mm_buy_now_click&view=" + view + "&value=" + value + "&nonce=" + nonce );
			} );
		</script>
		<?php
	}
}
add_action( 'admin_footer', 'mm_ux_log_buy_now_clicks_preview' );

function mm_ux_log_service_outbound() {
	if ( isset( $_GET['page'] ) && 'mojo-services' == $_GET['page'] ) {
		$event = array(
			't'		=> 'event',
			'ec'	=> 'user_action',
			'ea'	=> 'link_click',
			'el'	=> 'mojo_services_outbound',
		);
		mm_ux_log( $event );
	}
}
add_action( 'admin_init', 'mm_ux_log_service_outbound', 5 );

function mm_endpoint_action_buy_now_click() {
	if ( wp_verify_nonce( $_GET['nonce'], 'mm_nonce-buy_now_click' ) ) {
		$event = array(
				't'		=> 'event',
				'ec'	=> 'user_action',
				'ea'	=> 'buy_now_click',
				'el'	=> esc_attr( $_GET['view'] ),
				'ev'	=> esc_attr( $_GET['value'] ),
			);
		mm_ux_log( $event );
	} else {
		wp_die( 'Invalid Nonce' );
	}
	die();
}
add_action( 'wp_ajax_mm_buy_now_click', 'mm_endpoint_action_buy_now_click' );

function mm_ux_log_browse_all_themes() {
	if ( isset( $_GET['page'] ) && 'mojo-themes' == $_GET['page'] ) {
		?>
		<script type="text/javascript">
			jQuery( 'h2 .add-new-h2' ).click( function() {
				var nonce = "<?php echo wp_create_nonce( 'mm_nonce-browse_all_themes' ); ?>";
				jQuery.ajax( ajaxurl + "?action=mm_browse_all_themes&nonce=" + nonce );
			} );
		</script>
		<?php
	}
}
add_action( 'admin_footer', 'mm_ux_log_browse_all_themes' );

function mm_endpoint_action_browse_all_themes() {
	if ( wp_verify_nonce( $_GET['nonce'], 'mm_nonce-browse_all_themes' ) ) {
		$event = array(
			't'		=> 'event',
			'ec'	=> 'user_action',
			'ea'	=> 'link_click',
			'el'	=> 'browse_all_themes',
		);
		mm_ux_log( $event );
	}
	die();
}
add_action( 'wp_ajax_mm_browse_all_themes', 'mm_endpoint_action_browse_all_themes' );

function mm_ux_log_btn_click() {
	if ( isset( $_GET['page'] ) && 'mojo-themes' == $_GET['page'] ) {
		if ( isset( $_GET['btn'] ) ) {
			$event = array(
				't'		=> 'event',
				'ec'	=> 'user_action',
				'ea'	=> 'link_click',
				'el'	=> esc_attr( $_GET['btn'] ),
			);
			mm_ux_log( $event );
		}
	}
}
add_action( 'admin_footer', 'mm_ux_log_btn_click' );

function mm_jetpack_log_module_enabled( $module ) {
	$event = array(
		't'     => 'event',
		'ec'    => 'jetpack_event',
		'ea'    => 'module_enabled',
		'el'    => $module,
	);
	mm_ux_log( $event );
}
add_action( 'jetpack_pre_activate_module', 'mm_jetpack_log_module_enabled', 10, 1 );

function mm_jetpack_log_module_disabled( $module ) {
	$event = array(
		't'     => 'event',
		'ec'    => 'jetpack_event',
		'ea'    => 'module_disabled',
		'el'    => $module,
	);
	mm_ux_log( $event );
}
add_action( 'jetpack_pre_deactivate_module', 'mm_jetpack_log_module_disabled', 10, 1 );

function mm_jetpack_log_publicized( $submit_post, $post_id, $service_name, $connection ) {
	$event = array(
		't'     => 'event',
		'ec'    => 'jetpack_event',
		'ea'    => 'publicized',
		'el'    => $service_name,
	);
	mm_ux_log( $event );
}
add_action( 'publicize_save_meta', 'mm_jetpack_log_publicized', 10, 4 );

function mm_jetpack_log_connected( $entry ) {
	if ( 'register' == $entry['code'] ) {
		$event = array(
			't'     => 'event',
			'ec'    => 'jetpack_event',
			'ea'    => 'connected',
		);
		mm_ux_log( $event );
	}
}
add_action( 'jetpack_log_entry', 'mm_jetpack_log_connected', 10, 1 );

function mm_ux_site_launched( $new_option, $old_option ) {
	if ( $old_option != $new_option && 'true' == $new_option ) {
		$install_time = strtotime( get_option( 'mm_install_date', date( 'M d, Y' ) ) );
		$event = array(
			't'     => 'event',
			'ec'    => 'user_action',
			'ea'    => 'site_launched',
			'el'    => time() - $install_time,
		);
		mm_ux_log( $event );
	}
	return $new_option;
}
add_filter( 'pre_update_option_mm_coming_soon', 'mm_ux_site_launched', 10, 2 );

function mm_ux_auto_core_upgrade() {
	global $wp_version;
	$event = array(
		't'     => 'event',
		'ec'    => 'scheduled',
		'ea'    => 'auto_core_update',
		'el'    => $wp_version,
	);
	mm_ux_log( $event );
}
add_action( 'pre_update_option_auto_updater.lock', 'mm_ux_auto_core_upgrade' );

function mm_sso_success() {
	$event = array(
		't'     => 'event',
		'ec'    => 'user_action',
		'ea'    => 'sso',
		'el'    => 'success',
	);
	mm_ux_log( $event );
}
add_action( 'mmsso_success', 'mm_sso_success' );

function mm_sso_fail() {
	$event = array(
		't'     => 'event',
		'ec'    => 'user_action',
		'ea'    => 'sso',
		'el'    => 'fail',
	);
	mm_ux_log( $event );
}
add_action( 'mmsso_fail', 'mm_sso_fail' );
