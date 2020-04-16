<?php
/**
 * @package MOJO Marketplace WP Plugin
 */

$id       = sanitize_title_for_query( $_GET['item_id'] );
$response = mm_api_cache( 'https://api.mojomarketplace.com/api/v2/items/' . $id );
?>
<div id="mojo-wrapper" class="<?php echo mm_brand( 'mojo-%s-branding' ); ?>">
	<?php

	require_once MM_BASE_DIR . 'pages/header/header.php';

	if ( ! is_wp_error( $response ) ) {
		$body = json_decode( $response['body'] );
		$item = $body->items[0];
		$type = $item->type;
		?>
	<main id="main">
		<div class="container">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<ol class="breadcrumb">
								<li>
									<?php
									switch ( $item->type ) {
										case 'themes':
											echo '<a href="' . esc_url( add_query_arg( array( 'page' => 'mojo-themes' ), admin_url( 'admin.php' ) ) ) . '">' . esc_html__( 'WordPress Themes', 'mojo-marketplace-wp-plugin' ) . '</a>';
											$partner_type = 'themes';
											break;

										case 'plugins':
											echo '<a href="' . esc_url( add_query_arg( array( 'page' => 'mojo-plugins' ), admin_url( 'admin.php' ) ) ) . '">' . esc_html__( 'WordPress Plugins', 'mojo-marketplace-wp-plugin' ) . '</a>';
											$partner_type = 'plugins';
											break;

										case 'services':
											echo '<a href="' . esc_url( add_query_arg( array( 'page' => 'mojo-services' ), admin_url( 'admin.php' ) ) ) . '">' . esc_html__( 'Services', 'mojo-marketplace-wp-plugin' ) . '</a>';
											$partner_type = 'services';
											break;

										case 'graphics':
											echo '<a href="' . esc_url( add_query_arg( array( 'page' => 'mojo-graphics' ), admin_url( 'admin.php' ) ) ) . '">' . esc_html__( 'Graphics', 'mojo-marketplace-wp-plugin' ) . '</a>';
											$partner_type = 'graphics';
											break;

										default:
											echo '<a href="' . esc_url( add_query_arg( array( 'page' => 'mojo-themes' ), admin_url( 'admin.php' ) ) ) . '">' . esc_html__( 'WordPress Items', 'mojo-marketplace-wp-plugin' ) . '</a>';
											break;
									}
									?>
								</li>
								<li class="active">
								<?php
								echo substr( apply_filters( 'mm_item_name', $item->name ), 0, 39 );
								if ( strlen( $item->name ) != strlen( substr( $item->name, 0, 39 ) ) ) {
									echo '&hellip;';
								}
								?>
								</li>
							</ol>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-xs-12 col-sm-8">
							<div id="content">
								<h1><?php echo $item->name; ?></h1>
								<div class="meta-info">
									<?php mm_stars( $item->rating, $item->sales_count ); ?>
								</div>
								<div class="post-holder">
									<img style="max-width: 100%;" src="<?php echo $item->images->preview_url; ?>" />
									<hr/>
									<?php
									echo apply_filters( 'the_content', $item->description );
									?>
									<div class="hidden-xs widget text-center" style="border-width: 2px;">
										<div class="price">
											<span class="price-number"><?php esc_html_e( '$', 'mojo-marketplace-wp-plugin' ); ?><span><?php echo number_format( $item->prices->single_domain_license ); ?></span></span>
											<span class="currency"><?php esc_html_e( 'USD', 'mojo-marketplace-wp-plugin' ); ?></span>
										</div>
										<div class="btn-box">
											<a class="btn btn-success btn-lg mm_buy_now" data-id="<?php echo $item->id; ?>" href="
																											 <?php
																												echo mm_build_link(
																													add_query_arg( array( 'item_id' => $item->id ), 'https://www.mojomarketplace.com/cart' ),
																													array(
																														'utm_medium'  => 'plugin_admin',
																														'utm_content' => 'buy_now_single_bottom',
																													)
																												);
																												?>
																																	"><?php esc_html_e( 'Buy Now', 'mojo-marketplace-wp-plugin' ); ?></a>
										</div>
										<span class="price-option"><?php esc_html_e( 'One Time Fee', 'mojo-marketplace-wp-plugin' ); ?></span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-4">
							<aside id="sidebar">
								<div class="widget text-center">
									<div class="price">
										<span class="price-number">$<span><?php echo number_format( $item->prices->single_domain_license ); ?></span></span>
										<span class="currency"><?php esc_html_e( 'USD', 'mojo-marketplace-wp-plugin' ); ?></span>
									</div>
									<div class="btn-box">
										<a href="
										<?php
										echo mm_build_link(
											add_query_arg( array( 'item_id' => $item->id ), 'https://www.mojomarketplace.com/cart' ),
											array(
												'utm_medium'  => 'plugin_admin',
												'utm_content' => 'buy_now_single_sidebar',
											)
										);
										?>
													" class="btn btn-success btn-lg mm_buy_now" data-id="<?php echo $item->id; ?>" data-price="<?php echo number_format( $item->prices->single_domain_license ); ?>" data-view="single_item"><?php esc_html_e( 'Buy Now', 'mojo-marketplace-wp-plugin' ); ?></a>
									</div>
									<span class="price-option"><?php esc_html_e( 'One Time Fee', 'mojo-marketplace-wp-plugin' ); ?></span>
								</div>
								<div class="widget">
									<h3><?php esc_html_e( 'Item Information', 'mojo-marketplace-wp-plugin' ); ?></h3>
									<dl class="dl-horizontal">
										<dt><?php esc_html_e( 'Created:', 'mojo-marketplace-wp-plugin' ); ?></dt>
											<dd> <?php echo date( 'F j, Y', $item->created_timestamp ); ?></dd>
										<dt><?php esc_html_e( 'Updated:', 'mojo-marketplace-wp-plugin' ); ?></dt>
											<dd> <?php echo date( 'F j, Y', $item->modified_timestamp ); ?></dd>
										<?php if ( 'Professional Services' != $item->type ) { ?>
										<dt><?php esc_html_e( 'Sales:', 'mojo-marketplace-wp-plugin' ); ?></dt>
											<dd>
											<?php
											if ( ( $item->created_timestamp > time() - WEEK_IN_SECONDS * 4 ) && $item->sales_count < 10 ) {
												esc_html_e( 'New Item!', 'mojo-marketplace-wp-plugin' );
											} elseif ( ( $item->created_timestamp > time() - WEEK_IN_SECONDS * 4 ) && $item->sales_count > 10 ) {
												printf(
													/* translators: %s: number of sales */
													esc_html__( 'Popular New Item! (%s)', 'mojo-marketplace-wp-plugin' ),
													$item->sales_count
												);
											} else {
												echo number_format( $item->sales_count );
											}
											?>
											</dd>
										<?php } ?>
									</dl>
								</div>
								<?php if ( 'Professional Services' == $item->type ) { ?>
								<div class="widget">
									<h3><?php esc_html_e( 'Providers', 'mojo-marketplace-wp-plugin' ); ?></h3>
									<div class="avatar-block">
										<div class="avatar"><img class="provider-avatar" src="https://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( $item->service_provider->email ) ) ); ?>?s=36" /></div>
										<div class="name"><?php echo $item->service_provider->username; ?> </div>
									</div>
									<i><small><?php esc_html_e( 'Providers are all prescreened and approved.', 'mojo-marketplace-wp-plugin' ); ?></small></i>
								</div>
								<?php } ?>
								<?php
								if ( isset( $partner_type ) ) {
									$partner_offer = mm_partner_offers( $partner_type . '-single-item', false );
									if ( strlen( $partner_offer ) > 5 ) {
										echo sprintf( '<div class="widget">%s</div>', $partner_offer );
									}
									$partner_offer_2 = mm_partner_offers( $partner_type . '-single-item-2', false );
									if ( strlen( $partner_offer_2 ) > 5 ) {
										echo sprintf( '<div class="widget">%s</div>', $partner_offer_2 );
									}
								}
								?>
							</aside>
						</div>
					</div>
				</div>
			</div>

		</div>
	</main>
		<?php
	} else {
		mm_require( MM_BASE_DIR . 'pages/api-unavailable.php' );
	}
	?>
</div>
