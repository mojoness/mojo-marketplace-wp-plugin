<main id="main">
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						<ol class="breadcrumb">
							<li><?php esc_html_e( 'Unable to load items from API. Please try again.', 'mojo-marketplace-wp-plugin' ); ?> <a href="<?php echo esc_url( add_query_arg( array( 'refresh' => rand( 100, 999 ) ) ) ); ?>"><?php esc_html_e( 'Try Again', 'mojo-marketplace-wp-plugin' ); ?></a></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
