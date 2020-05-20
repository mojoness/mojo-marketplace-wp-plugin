<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<div id="mojo-wrapper" class="<?php echo mm_brand( 'mojo-%s-branding' ); ?>">
<?php
require_once MM_BASE_DIR . 'pages/header/header.php';
$is_bluerock = file_exists( '/opt/cpanel/ea-php70/root/usr/bin/php-cgi' );
$links       = array(
	'bluerock' => array(
		'sites'       => 'https://my.bluehost.com/cgi/app/#/sites/' . mm_site_bin2hex(),
		'performance' => 'https://my.bluehost.com/cgi/app/#/sites/' . mm_site_bin2hex() . '/performance',
		'email'       => 'https://my.bluehost.com/cgi/email_manager',
		'domains'     => 'https://my.bluehost.com/cgi/dm',
		'support'     => 'https://helpchat.bluehost.com/',
	),
	'legacy'   => array(
		'sites'       => 'https://my.bluehost.com/hosting/wordpress_tools/' . mm_site_bin2hex(),
		'performance' => 'https://my.bluehost.com/hosting/wordpress_tools/performance/' . mm_site_bin2hex(),
		'email'       => 'https://my.bluehost.com/cgi/email_manager',
		'domains'     => 'https://my.bluehost.com/cgi/dm',
		'support'     => 'https://helpchat.bluehost.com/',
	),
);

if ( $is_bluerock ) {
	$links = $links['bluerock'];
} else {
	$links = $links['legacy'];
}

?>

	<main id="main" class="home">
		<div class="container">
			<?php do_action( 'mojo_home_top' ); ?>
			<div class="row">
				<h2 id="content"><?php esc_html_e( 'Content', 'mojo-marketplace-wp-plugin' ); ?></h2>
				<div class="col-xs-12 col-sm-6">
					<div class="panel panel-default panel-body">
						<div>
							<span class="pull-left dashicons dashicons-admin-post"></span>
							<h2><?php esc_html_e( 'Blog Posts', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<p><?php esc_html_e( 'Add blog posts to your site. You can also organize them into categories.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<div class="col-xs-12 col-sm-12 text-right">
								<a class="btn btn-link btn-md" href="<?php echo add_query_arg( array( 'taxonomy' => 'category' ), admin_url( 'edit-tags.php' ) ); ?>"><small><?php esc_html_e( 'Manage Categories', 'mojo-marketplace-wp-plugin' ); ?></small></a>
								<a class="btn btn-primary btn-md" href="<?php echo admin_url( 'post-new.php' ); ?>"><?php esc_html_e( 'Add New Post', 'mojo-marketplace-wp-plugin' ); ?></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="panel panel-default panel-body">
						<div>
						<span class="pull-left dashicons dashicons-admin-page"></span>
							<h2><?php esc_html_e( 'Pages', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<p><?php esc_html_e( 'Add pages to your website easily by clicking add new page.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<div class="col-xs-12 col-sm-8 col-sm-offset-4 text-right">
								<a class="btn btn-primary btn-md" href="<?php echo add_query_arg( array( 'post_type' => 'page' ), admin_url( 'post-new.php' ) ); ?>"><?php esc_html_e( 'Add New Page', 'mojo-marketplace-wp-plugin' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="panel panel-default panel-body">
						<div>
						<span class="pull-left dashicons dashicons-menu"></span>
							<h2><?php esc_html_e( 'Navigation Menus', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<p><?php esc_html_e( 'If you are looking to adjust or control your navigation of your website simply click manage below and rearrange your menus.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<div class="col-xs-12 col-sm-8 col-sm-offset-4 text-right">
								<a class="btn btn-primary btn-md" href="<?php echo admin_url( 'nav-menus.php' ); ?>"><?php esc_html_e( 'Manage', 'mojo-marketplace-wp-plugin' ); ?></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="panel panel-default panel-body">
						<div>
							<div class="col-xs-12 clearfix">
								<span class="pull-left dashicons dashicons-cart"></span>
								<h2 class="pull-left"><?php esc_html_e( 'Sell Products', 'mojo-marketplace-wp-plugin' ); ?></h2>
								<img class="pull-right" src="<?php echo plugins_url( '../images/woocommerce.png', __FILE__ ); ?>" width="150" />
							</div>
							<p><?php esc_html_e( 'Are you are looking to sell products on your WordPress website? If so, we recommend that you install and setup WooCommerce.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<div class="col-xs-12 col-sm-8 col-sm-offset-4 text-right">
								<?php
								if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
									echo '<a class="btn btn-primary btn-md" href="' . add_query_arg( array( 'post_type' => 'product' ), esc_url( admin_url( 'edit.php' ) ) ) . '">' . esc_html__( 'Manage Products', 'mojo-marketplace-wp-plugin' ) . '</a>';
								} elseif ( file_exists( WP_CONTENT_DIR . '/plugins/woocommerce/woocommerce.php' ) ) {
									echo '<a class="btn btn-primary btn-md" href="' . wp_nonce_url( 'plugins.php?action=activate&plugin=' . urlencode( 'woocommerce/woocommerce.php' ), 'activate-plugin_woocommerce/woocommerce.php' ) . '">' . esc_html__( 'Activate WooCommerce', 'mojo-marketplace-wp-plugin' ) . '</a>';
								} else {
									echo '<a class="btn btn-primary btn-md" href="' . wp_nonce_url( admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ), 'install-plugin_woocommerce' ) . '">' . esc_html__( 'Install WooCommerce', 'mojo-marketplace-wp-plugin' ) . '</a>';
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<h2 id="design"><?php esc_html_e( 'Design &amp; Build', 'mojo-marketplace-wp-plugin' ); ?></h2>
				<div class="col-xs-12 col-sm-7">
					<div class="panel panel-default panel-body">
						<div>
						<span class="pull-left dashicons dashicons-admin-customizer"></span>
							<h2><?php esc_html_e( 'Customizer', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<p><?php esc_html_e( 'Customize your theme from the front end and view your changes before updating them.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<div class="col-xs-12 col-sm-8 col-sm-offset-4 text-right">
								<a class="btn btn-primary btn-md" href="
								<?php
								echo add_query_arg(
									array(
										'return' => add_query_arg( array( 'page' => 'mojo-home' ) ),
										admin_url( 'admin.php' ),
									),
									admin_url( 'customize.php' )
								);
								?>
								"><?php esc_html_e( 'Customize Theme', 'mojo-marketplace-wp-plugin' ); ?></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-5">
					<div class="panel panel-default panel-body">
						<div class="clearfix">
						<span class="pull-left dashicons dashicons-admin-appearance"></span>
							<h2><?php esc_html_e( 'WordPress Themes', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<p><?php esc_html_e( 'Easily browse and find a theme that inspires you!', 'mojo-marketplace-wp-plugin' ); ?></p>

							<div class="btn-group home-btn-group clearfix panel-body col-sm-12">
								<a href="
								<?php
								echo add_query_arg(
									array(
										'page'    => 'mojo-marketplace',
										'section' => 'mixed-themes',
										'type'    => 'premium',
									),
									admin_url( 'admin.php' )
								);
								?>
								" class="btn btn-default btn-md"><?php esc_html_e( 'Premium Themes', 'mojo-marketplace-wp-plugin' ); ?></a>
								<a href="
								<?php
								echo add_query_arg(
									array(
										'page'    => 'mojo-marketplace',
										'section' => 'mixed-themes',
										'type'    => 'free',
									),
									admin_url( 'admin.php' )
								);
								?>
								" class="btn btn-primary btn-md"><?php esc_html_e( 'Free Themes', 'mojo-marketplace-wp-plugin' ); ?></a>
							</div>

						</div>
					</div>
				</div>
			</div>

			<?php if ( 'compatible' === get_transient( 'mm_compat_check' ) ) { ?>
			<div class="row">
				<div class="col-xs-12 col-sm-12">
					<div class="panel panel-default panel-body">
						<div>
						<span class="pull-left dashicons dashicons-screenoptions"></span>
							<h2><?php esc_html_e( 'Staging', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<p><?php esc_html_e( 'Staging allows you to create a seperate copy of your site that only you can see. You can test new ideas there before going live.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<div class="col-xs-12 col-sm-8 col-sm-offset-4 text-right">
								<a class="btn btn-primary btn-md" href="<?php echo add_query_arg( array( 'page' => 'mojo-staging' ), admin_url( 'admin.php' ) ); ?>"><?php esc_html_e( 'Get Started', 'mojo-marketplace-wp-plugin' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>

			<?php if ( is_plugin_active( 'jetpack/jetpack.php' ) ) { ?>
			<div class="row">
				<h2 id="traffic"><?php esc_html_e( 'Traffic &amp; Engagement', 'mojo-marketplace-wp-plugin' ); ?></h2>
				<?php $te_size = ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'stats' ) ) ? 12 : 6; ?>
				<div class="col-xs-12 col-sm-<?php echo $te_size; ?>">
					<div class="panel panel-default panel-body">
						<div>
						<span class="pull-left dashicons dashicons-share"></span>
							<h2><?php esc_html_e( 'Social', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<p><?php esc_html_e( 'Add social sharing buttons to your site for Facebook, Twitter, and others, so your visitors can share your content with their friends.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<div class="col-xs-12 col-sm-8 col-sm-offset-4 text-right">
								<a class="btn btn-primary btn-md" href="<?php echo add_query_arg( array( 'page' => 'jetpack#/sharing' ), admin_url( 'admin.php' ) ); ?>"><?php esc_html_e( 'Manage', 'mojo-marketplace-wp-plugin' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="panel panel-default panel-body">
						<div>
						<span class="pull-left dashicons dashicons-megaphone"></span>
							<h2><?php esc_html_e( 'Publicize', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<p><?php esc_html_e( 'Share your content with your social networks automatically when you publish content on your site.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<div class="col-xs-12 col-sm-8 col-sm-offset-4 text-right">
								<a class="btn btn-primary btn-md" href="<?php echo add_query_arg( array( 'page' => 'jetpack#/sharing' ), admin_url( 'admin.php' ) ); ?>"><?php esc_html_e( 'Configure', 'mojo-marketplace-wp-plugin' ); ?></a>
							</div>
						</div>
					</div>
				</div>
				<?php
				if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'stats' ) ) {
					?>
				<div class="col-xs-12 col-sm-6">
					<div class="panel panel-default panel-body">
						<div>
						<span class="pull-left dashicons dashicons-chart-bar"></span>
							<h2><?php esc_html_e( 'Stats', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<p><?php esc_html_e( 'Stats show you which posts are most popular. You can even stay informed of when you get surges in traffic.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<div class="col-xs-12 col-sm-8 col-sm-offset-4 text-right">
								<a class="btn btn-default btn-md" href="<?php echo add_query_arg( array( 'page' => 'stats' ), admin_url( 'admin.php' ) ); ?>"><?php esc_html_e( 'View Stats', 'mojo-marketplace-wp-plugin' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
			<?php } ?>
			<?php if ( 'bluehost' == mm_brand() ) { ?>
			<div class="row">
				<h2 id="performance"><?php esc_html_e( 'Performance', 'mojo-marketplace-wp-plugin' ); ?></h2>
				<div class="col-xs-12 col-sm-6">
					<div class="panel panel-default panel-body text-center performance-card">
						<div>
						<span class="dashicons dashicons-performance"></span>
							<h2><?php esc_html_e( 'Page Cache', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<p><?php esc_html_e( 'Page caching allows your server to keep a copy of a page for a short time to dramatically improve speed.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<div class="col-xs-12 col-sm-12">
								<a class="btn btn-primary btn-md" href="<?php echo add_query_arg( array( 'page' => 'mojo-performance' ), admin_url( 'admin.php' ) ); ?>"><?php esc_html_e('Configure', 'mojo-marketplace-wp-plugin' ); ?></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="panel panel-default panel-body text-center performance-card">
						<div>
						<div><span class="dashicons dashicons-visibility"></span></div>
							<h2><?php esc_html_e( 'Photon', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<p><?php esc_html_e( 'Photon is an image acceleration service that will resize your images and serve them from a CDN.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<div class="col-xs-12 col-sm-12">
								<?php
								if ( is_plugin_active( 'jetpack/jetpack.php' ) ) {
									echo '<a class="btn btn-primary btn-md" href="' . add_query_arg( array( 'page' => 'jetpack#/dashboard' ), esc_url( admin_url( 'admin.php' ) ) ) . '">' . esc_html__( 'Learn More', 'mojo-marketplace-wp-plugin' ) . '</a>';
								} elseif ( file_exists( WP_CONTENT_DIR . '/plugins/jetpack/jetpack.php' ) ) {
									echo '<a class="btn btn-primary btn-md" href="' . wp_nonce_url( 'plugins.php?action=activate&plugin=' . urlencode( 'jetpack/jetpack.php' ), 'activate-plugin_jetpack/jetpack.php' ) . '">' . esc_html__( 'Activate Jetpack', 'mojo-marketplace-wp-plugin' ) . '</a>';
								} else {
									echo '<a class="btn btn-primary btn-md" href="' . wp_nonce_url( admin_url( 'update.php?action=install-plugin&plugin=jetpack' ), 'install-plugin_jetpack' ) . '">' . esc_html__( 'Install Jetpack', 'mojo-marketplace-wp-plugin' ) . '</a>';
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<h2 id="hosting"><?php esc_html_e( 'Hosting', 'mojo-marketplace-wp-plugin' ); ?></h2>
				<div class="col-xs-12 col-sm-6">
					<div class="panel panel-default panel-body">
						<div>
						<span class="pull-left dashicons dashicons-desktop"></span>
							<h2><?php esc_html_e( 'Manage My Sites', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<p><?php esc_html_e( "Manage your site from Bluehost's control panel. You can take backups, keep things secure, and improve performance.", 'mojo-marketplace-wp-plugin' ); ?></p>
							<div class="col-xs-12 col-sm-8 col-sm-offset-4 text-right">
								<a class="btn btn-primary btn-md" href="
								<?php
								echo mm_build_link(
									$links['sites'],
									array(
										'utm_campaign' => mm_brand( '%s_wp_plugin' ),
										'utm_medium'   => 'plugin_home',
										'utm_content'  => 'manage_sites',
										'r'            => '',
									)
								);
								?>
																		" target="_blank"><?php esc_html_e( 'Manage', 'mojo-marketplace-wp-plugin' ); ?></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="panel panel-default panel-body">
						<div>
						<span class="pull-left dashicons dashicons-email-alt"></span>
							<h2><?php esc_html_e( 'Email', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<p><?php esc_html_e( 'Create accounts, compose, send, and recieve all your email in your Bluehost control panel.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<div class="col-xs-12 col-sm-8 col-sm-offset-4 text-right">
								<a class="btn btn-primary btn-md" href="
								<?php
								echo mm_build_link(
									$links['email'],
									array(
										'utm_campaign' => mm_brand( '%s_wp_plugin' ),
										'utm_medium'   => 'plugin_home',
										'utm_content'  => 'manage_email',
										'r'            => '',
									)
								);
								?>
																		" target="_blank"><?php esc_html_e( 'Manage', 'mojo-marketplace-wp-plugin' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="panel panel-default panel-body">
						<div>
						<span class="pull-left dashicons dashicons-admin-site"></span>
							<h2><?php esc_html_e( 'Domains', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<p><?php esc_html_e( 'Find a new domain and assign it to your site, or start a new site with a new domain.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<div class="col-xs-12 col-sm-8 col-sm-offset-4 text-right">
								<a class="btn btn-primary btn-md" href="
								<?php
								echo mm_build_link(
									$links['domains'],
									array(
										'utm_campaign' => mm_brand( '%s_wp_plugin' ),
										'utm_medium'   => 'plugin_home',
										'utm_content'  => 'find_domain',
										'r'            => '',
									)
								);
								?>
																		" target="_blank"><?php esc_html_e( 'Find a Domain', 'mojo-marketplace-wp-plugin' ); ?></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="panel panel-default panel-body">
						<div>
						<span class="pull-left dashicons dashicons-editor-help"></span>
							<h2><?php esc_html_e( 'Help', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<p><?php esc_html_e( 'Need help from the folks at Bluehost? We have 24/7 US-based phone and chat support waiting to help.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<div class="col-xs-12 col-sm-8 col-sm-offset-4 text-right">
								<a class="btn btn-primary btn-md" href="
								<?php
								echo mm_build_link(
									$links['support'],
									array(
										'utm_campaign' => mm_brand( '%s_wp_plugin' ),
										'utm_medium'   => 'plugin_home',
										'utm_content'  => 'help',
										'r'            => '',
									)
								);
								?>
																		" target="_blank"><?php esc_html_e( 'Help Me', 'mojo-marketplace-wp-plugin' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</main>
</div>

<script type="text/javascript">
	jQuery( document ).ready( function( $ ) {
		$( '.scroll' ).on( 'click touchstart' , function ( event ) {
			event.preventDefault();
			var dest = 0;
			if ( $( this.hash ).offset().top > $( document ).height() - $( window ).height() ) {
				dest = $( document ).height() - $( window ).height();
			} else {
				dest = $( this.hash ).offset().top;
			}
			$( 'html,body' ).animate( {
				scrollTop: dest
			}, Math.round( dest * 1.2 ), 'swing' );
		} );
	} );
</script>
