<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div id="mojo-wrapper" class="<?php echo mm_brand( 'mojo-%s-branding' ); ?>">
<?php
require_once MM_BASE_DIR . 'pages/header/header.php';
?>
	<main id="main">
		<div class="container">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php
					if ( isset( $_GET['staging-created'] ) || get_transient( 'mm_fresh_staging' ) ) {
						delete_transient( 'mm_fresh_staging' );
						?>
					<div class="row">
						<div id="staging-success" class="col-xs-12 col-sm-12 text-center">
							<div class="checkmark">
								<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
								viewBox="0 0 161.2 161.2" enable-background="new 0 0 161.2 161.2" xml:space="preserve">
									<path class="path" fill="none" stroke="#8ad332 " stroke-miterlimit="10" d="M425.9,52.1L425.9,52.1c-2.2-2.6-6-2.6-8.3-0.1l-42.7,46.2l-14.3-16.4
								c-2.3-2.7-6.2-2.7-8.6-0.1c-1.9,2.1-2,5.6-0.1,7.7l17.6,20.3c0.2,0.3,0.4,0.6,0.6,0.9c1.8,2,4.4,2.5,6.6,1.4c0.7-0.3,1.4-0.8,2-1.5
								c0.3-0.3,0.5-0.6,0.7-0.9l46.3-50.1C427.7,57.5,427.7,54.2,425.9,52.1z"/>
									<circle class="path" fill="none" stroke="#8bd331 " stroke-width="4" stroke-miterlimit="10" cx="80.6" cy="80.6" r="62.1"/>
									<polyline class="path" fill="none" stroke="#8ad332 " stroke-width="7" stroke-linecap="round" stroke-miterlimit="10" points="113,52.8
								74.1,108.4 48.2,86.4 "/>
									<circle class="spin" fill="none" stroke="#8ad332 " stroke-width="4" stroke-miterlimit="10" stroke-dasharray="12.2175,12.2175" cx="80.6" cy="80.6" r="73.9"/>
								</svg>
							</div>
							<h1><?php esc_html_e( 'Your staging environment is ready!', 'mojo-marketplace-wp-plugin' ); ?></h1>
						</div>
					</div>
					<?php } ?>
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<ol class="breadcrumb">
								<li><?php esc_html_e( 'Production Environment', 'mojo-marketplace-wp-plugin' ); ?></li>
							</ol>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<p><?php esc_html_e( 'Below are the details of your staging environment.', 'mojo-marketplace-wp-plugin' ); ?></p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-8">
							<?php
							$config = get_option( 'staging_config' );
							?>
							<table class="table table-bordered">

								<?php if ( isset( $config['staging_url'] ) ) : ?>
									<tr>
										<td>
											<?php esc_html_e( 'Staging URL', 'mojo-marketplace-wp-plugin' ); ?>
										</td>
										<td>
											<a href="<?php echo esc_url( $config['staging_url'] ); ?>" target="_blank"><?php echo esc_url( $config['staging_url'] ); ?></a>
										</td>
									</tr>
								<?php endif; ?>

								<?php if ( isset( $config['staging_dir'] ) ) : ?>
									<tr>
										<td>
											<?php esc_html_e( 'Staging Directory', 'mojo-marketplace-wp-plugin' ); ?>
										</td>
										<td>
											<?php esc_html( $config['staging_dir'] ); ?>
										</td>
									</tr>
								<?php endif; ?>

								<?php if ( isset( $config['creation_date'] ) ) : ?>
									<tr>
										<td>
											<?php esc_html_e( 'Creation Date', 'mojo-marketplace-wp-plugin' ); ?>
										</td>
										<td>
											<?php echo esc_html( $config['creation_date'] ); ?>
										</td>
									</tr>
								<?php endif; ?>
							</table>
						</div>
						<div class="col-xs-12 col-sm-4">
							<button class="btn btn-success btn-lg staging-action" data-staging-action="mm_sso_staging"><?php esc_html_e( 'Go To Staging Site', 'mojo-marketplace-wp-plugin' ); ?></button>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<p><?php esc_html_e( 'Ready to deploy your changes? Go to your staging site and deploy from the administration panel.', 'mojo-marketplace-wp-plugin' ); ?></p>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<ol class="breadcrumb">
								<li><?php esc_html_e( 'Staging Options', 'mojo-marketplace-wp-plugin' ); ?></li>
							</ol>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-xs-12 col-sm-6 text-center">
							<h2><?php esc_html_e( 'Clone Production To Staging', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<div style="height: 150px;">
								<img src="<?php echo esc_url( MM_ASSETS_URL . 'img/production-to-staging.png' ); ?>" />
							</div>
							<p><?php esc_html_e( 'Copy your current production site and your settings to your staging environment.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<button class="btn btn-primary btn-lg staging-action" data-staging-action="mm_clone"><?php esc_html_e( 'Clone', 'mojo-marketplace-wp-plugin' ); ?></button>
						</div>
						<div class="col-xs-12 col-sm-6 text-center">
							<h2><?php esc_html_e( 'Destroy Staging Environment', 'mojo-marketplace-wp-plugin' ); ?></h2>
							<div style="height: 150px;">
								<img src="<?php echo esc_url( MM_ASSETS_URL . 'img/destroy-staging.png' ); ?>" />
							</div>
							<p><?php esc_html_e( 'Need to start fresh? This will completely remove your current staging environment.', 'mojo-marketplace-wp-plugin' ); ?></p>
							<button class="btn btn-primary btn-lg mm-modal" data-mm-modal="destroy-confirm"><?php esc_html_e( 'Destroy Staging', 'mojo-marketplace-wp-plugin' ); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
</div>

<script type="text/javascript" src="https://api.mojomarketplace.com/mojo-plugin-assets/js/staging.js"></script>
