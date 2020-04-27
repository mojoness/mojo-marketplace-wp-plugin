<header id="header" class="navbar navbar-default">
	<div class="header-block bg-cover" style="background-image: url('<?php echo mm_brand( MM_ASSETS_URL . 'img/header-bg-%s.jpg' ); ?>');">
		<span data-srcset="<?php echo mm_brand( MM_ASSETS_URL . 'img/header-bg-%s.jpg' ); ?>, <?php echo mm_brand( MM_ASSETS_URL . 'img/header-bg-%s-2x.jpg' ); ?> 2x"></span>
		<nav>
			<div class="container">
				<div class="inner-holder">
					<a class="navbar-brand" href="#">
						<img src="<?php echo mm_brand( MM_ASSETS_URL . 'img/logo-icon-%s.svg' ); ?>" alt="<?php esc_attr_e( 'Marketplace', 'mojo-marketplace-wp-plugin' ); ?>">
					</a>
				</div>
			</div>
			<div class="collapse navbar-collapse" id="navbar-collapse-1" style="margin-top: 40px;">
				<div class="container">
					<div class="inner-holder">
						<div class="nav-holder clearfix">
							<ul class="nav navbar-nav justified-nav">
								<li>
									<a class="scroll" href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-home' ), admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Home', 'mojo-marketplace-wp-plugin' ); ?></a>
								</li>
								<li class="active">
									<a class="scroll" href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page'    => 'mojo-marketplace',
												'section' => 'themes',
											),
											admin_url( 'admin.php' )
										)
									);
									?>
									"><?php esc_html_e( 'Marketplace', 'mojo-marketplace-wp-plugin' ); ?></a>
								</li>
								<?php if ( 'bluehost' == mm_brand() ) { ?>
								<li>
									<a class="scroll" href="<?php echo esc_url( add_query_arg( array( 'page' => 'mojo-performance' ), admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Site Performance', 'mojo-marketplace-wp-plugin' ); ?></a>
								</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="sub-navbar collapse navbar-collapse" id="navbar-collapse-1">
				<div class="container">
					<div class="inner-holder">
						<div class="nav-holder clearfix">
							<ul class="nav sub-navbar-nav navbar-nav justified-nav">
				<li>
									<a href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page'    => 'mojo-marketplace',
												'section' => 'themes',
											),
											admin_url( 'admin.php' )
										)
									);
									?>
									"><?php esc_html_e( 'Themes', 'mojo-marketplace-wp-plugin' ); ?></a>
								</li>
				<li>
									<a href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page'    => 'mojo-marketplace',
												'section' => 'plugins',
											),
											admin_url( 'admin.php' )
										)
									);
									?>
									"><?php esc_html_e( 'Plugins', 'mojo-marketplace-wp-plugin' ); ?></a>
								</li>
				<li>
									<a href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page'    => 'mojo-marketplace',
												'section' => 'graphics',
											),
											admin_url( 'admin.php' )
										)
									);
									?>
									"><?php esc_html_e( 'Graphics', 'mojo-marketplace-wp-plugin' ); ?></a>
								</li>
				<li>
									<a href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page'    => 'mojo-marketplace',
												'section' => 'business-tools',
											),
											admin_url( 'admin.php' )
										)
									);
									?>
									"><?php esc_html_e( 'Business Tools', 'mojo-marketplace-wp-plugin' ); ?></a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="bh-loader"></div>
		</nav>
	</div>
</header>
