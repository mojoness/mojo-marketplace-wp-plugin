<?php
$nav = sanitize_key( $_GET['page'] );

switch ( $nav ) {
	case 'mojo-home':
		$subnav = array(
			0 => array( 'class' => 'scroll', 'href' => '#content', 'content' => esc_html__( 'Site Content' ) ),
			1 => array( 'class' => 'scroll', 'href' => '#design', 'content' => esc_html__( 'Design &amp; Build' ) ),

		);
		if ( is_plugin_active( 'jetpack/jetpack.php' ) ) {
			$subnav[2] = array( 'class' => 'scroll', 'href' => '#traffic', 'content' => esc_html__( 'Traffic &amp; Engagement' ) );
		}
		if ( 'bluehost' == mm_brand() ) {
			$subnav[3] = array( 'class' => 'scroll', 'href' => '#performance', 'content' => esc_html__( 'Site Performance' ) );
			$subnav[4] = array( 'class' => 'scroll', 'href' => '#hosting', 'content' => esc_html__( 'Hosting' ) );
		}
		ksort( $subnav );
		break;
	case 'mojo-marketplace':
		$subnav = array(
			'themes' => array( 'href' => add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes' ), admin_url( 'admin.php' ) ), 'content' => esc_html__( 'Themes' ) ),
			'plugins' => array( 'href' => add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'plugins' ), admin_url( 'admin.php' ) ), 'content' => esc_html__( 'Plugins' ) ),
			'services' => array( 'href' => add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'services' ), admin_url( 'admin.php' ) ), 'content' => esc_html__( 'Services' ) ),
			'graphics' => array( 'href' => add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'graphics' ), admin_url( 'admin.php' ) ), 'content' => esc_html__( 'Graphics' ) )
		);
		if ( false !== get_transient( '_mm_session_token' ) ) {
			$subnav['purchases'] = array( 'href' => add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'purchases' ), admin_url( 'admin.php' ) ), 'content' => esc_html__( 'My Purchases' ) );
		}

		if ( isset( $_GET['section'] ) && array_key_exists( $_GET['section'], $subnav ) ) {
			$subnav[ $_GET['section'] ]['active'] = true;
		}
		break;
	case 'mojo-performance':
		$subnav = array(
			array( 'href' => add_query_arg( array( 'page' => 'mojo-performance' ), admin_url( 'admin.php' ) ), 'content' => esc_html__( 'All' ) ),
		);
		if ( mm_brand() == 'bluehost' ) {
			$subnav[] = array( 'href' => 'https://my.bluehost.com/hosting/wordpress_tools/performance/', 'content' => esc_html__( 'CDN' ) );
		}
		if ( is_plugin_active( 'jetpack/jetpack.php' ) ) {
			$subnav[] = array( 'href' => add_query_arg( array( 'page' => 'jetpack#/dashboard' ), admin_url( 'admin.php' ) ), 'content' => esc_html__( 'Photon' ) );
		}

		break;
	case 'mojo-staging':
		$subnav = array();
		break;
	default:
		$subnav = array();
		break;
}
if ( empty( $subnav ) ) {
	return;
}
?>
<div class="sub-navbar collapse navbar-collapse">
	<div class="container">
		<div class="inner-holder">
			<div class="nav-holder clearfix">
				<ul class="nav sub-navbar-nav navbar-nav justified-nav">
					<?php
					foreach ( $subnav as $navitem ) {
						if ( ! isset( $navitem['active'] ) ) {
							echo '<li>';
						} else {
							echo '<li class="active">';
						}
						if ( ! isset( $navitem['class'] ) ) {
							echo '<a href="' . $navitem['href'] . '">' . $navitem['content'] . '</a>';
						} else {
							echo '<a class="' . $navitem['class'] . '" href="' . $navitem['href'] . '">' . $navitem['content'] . '</a>';
						}
						echo '</li>';
					}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>
