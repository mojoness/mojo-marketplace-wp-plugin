<div class="collapse navbar-collapse" id="navbar-collapse-1">
	<div class="container">
		<div class="inner-holder">
			<div class="nav-holder clearfix">
				<ul class="nav navbar-nav justified-nav">
					<li <?php if ( 'themes' == $_GET['section'] ) { echo 'class="active"'; } ?>>
						<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes' ), admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Themes' ); ?></a>
						<div class="dropdown">
							<ul class="menu">
								<li class="popular"><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'sort' => 'popular' ), admin_url( 'admin.php' ) ) ); ?>"><strong><?php esc_html_e( 'Popular' ); ?></strong></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'items' => 'blog' ),         admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Blog' ); ?></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'items' => 'business' ),     admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Business' ); ?></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'items' => 'church' ),       admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Church' ); ?></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'items' => 'woocommerce' ),  admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'WooCommerce' ); ?></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'items' => 'fashion' ),      admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Fashion' ); ?></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'items' => 'fitness' ),      admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Fitness' ); ?></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'items' => 'health-care' ),  admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Health' ); ?></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'items' => 'landing-page' ), admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Landing Page' ); ?></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'items' => 'magazine' ),     admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Magazine' ); ?></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'items' => 'photography' ),  admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Photography' ); ?></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'items' => 'portfolio' ),    admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Portfolio' ); ?></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'items' => 'real-estate' ),  admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Real Estate' ); ?></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'items' => 'restaurant' ),   admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Restaurant' ); ?></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'items' => 'sports' ),       admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Sports' ); ?></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'themes', 'items' => 'travel' ),       admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Travel' ); ?></a></li>
							</ul>
						</div>
					</li>
					<li <?php if ( 'plugins' == $_GET['section'] ) { echo 'class="active"'; } ?>>
						<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'plugins' ), admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Plugins' ); ?></a>
					</li>
					<li <?php if ( 'services' == $_GET['section'] ) { echo 'class="active"'; } ?>>
						<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'services' ), admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Services' ); ?></a>
					</li>
					<li <?php if ( 'graphics' == $_GET['section'] ) { echo 'class="active"'; } ?>>
						<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'graphics' ), admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Graphics' ); ?></a>
						<div class="dropdown">
							<ul class="menu">
								<li class="popular"><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'graphics', 'sort' => 'popular' ), admin_url( 'admin.php' ) ) ); ?>"><strong><?php esc_html_e( 'Popular' ); ?></strong></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'graphics', 'items' => 'logo' ), admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Stock Logos' ); ?></a></li>
								<li><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'graphics', 'items' => 'business-cards' ), admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Business Cards' ); ?></a></li>
							</ul>
						</div>
					</li>
					<li <?php if ( 'business-tools' == $_GET['section'] ) { echo 'class="active"'; } ?>>
						<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-marketplace', 'section' => 'business-tools' ), admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Business Tools' ); ?></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
