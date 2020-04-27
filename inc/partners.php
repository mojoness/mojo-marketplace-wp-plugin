<?php

/*
 * Mojo/Bluehost Partners code
 */

 // WPForms Upgrade Affiliate link
function mm_wpforms_upgrade_affiliate_link( $url ) {
	return 'http://www.shareasale.com/r.cfm?B=837827&U=1258907&M=64312&urllink=' . rawurlencode( $url );
}
add_filter( 'wpforms_upgrade_link', 'mm_wpforms_upgrade_affiliate_link' );

function mm_ecomdash_dashboard_promo() {
	global $wp_meta_boxes;

	$is_woo = is_plugin_active( 'woocommerce/woocommerce.php' );
	$brand  = get_option( 'mm_brand', false );

	if ( $is_woo && 'BlueHost' === $brand ) {
		$wp_meta_boxes['dashboard']['normal']['core'] = array(
			'mm_ecomdash_promo' => array(
				'id'       => 'mm_ecomdash_promo',
				'title'    => __( 'ecomdash + Bluehost', 'mojo-marketplace-wp-plugin' ),
				'callback' => 'mm_ecomdash_bh_content',
				'args'     => array(
					'__widget_basename' => __( 'ecomdash + Bluehost', 'mojo-marketplace-wp-plugin' ),
				),
			),
		) + $wp_meta_boxes['dashboard']['normal']['core'];
	}

	if ( $is_woo && false !== strpos( $brand, 'HostGator' ) ) {
		$wp_meta_boxes['dashboard']['normal']['core'] = array(
			'mm_ecomdash_promo' => array(
				'id'       => 'mm_ecomdash_promo',
				'title'    => __( 'ecomdash + HostGator', 'mojo-marketplace-wp-plugin' ),
				'callback' => 'mm_ecomdash_hg_content',
				'args'     => array(
					'__widget_basename' => __( 'ecomdash + HostGator', 'mojo-marketplace-wp-plugin' ),
				),
			),
		) + $wp_meta_boxes['dashboard']['normal']['core'];
	}

}
add_action( 'wp_dashboard_setup', 'mm_ecomdash_dashboard_promo', 100 );

function mm_ecomdash_bh_content() {
	?>
	<style type="text/css">
		#ecomdash-bluehost {
			position:relative;
		}
		#ecomdash-bluehost img {
			width:100%;
			height:auto;
		}
		#ecomdash-bluehost div {
			position: absolute;
			bottom: 15%;
			text-align:
			center;width: 100%;
		}
		#ecomdash-bluehost .promo-btn-primary,
		#ecomdash-bluehost .promo-btn-secondary:hover {
			background-color: #fff;
			color: #4276cc;
			padding: 10px 20px;
			border-radius:4px;
			text-decoration: none;
			font-size: 16px;
			margin: 2px;
			border: 1px solid #fff;
		}
		#ecomdash-bluehost .promo-btn-secondary,
		#ecomdash-bluehost .promo-btn-primary:hover {
			background-color: #4276cc;
			color: #fff;
			padding: 10px 20px;
			border-radius:4px;
			text-decoration: none;
			font-size: 16px;
			border: 1px solid #fff;
			margin:2px;
		}
	</style>
	<div id="ecomdash-bluehost">
		<img src="https://mojomarketplace.com/mojo-plugin-assets/img/ecomdash-bluehost.png" alt="<?php esc_attr_e( 'Ecomdash and Bluehost. Ecomdash brings all your data together so you can sell everywhere, and manage it all from one easy place. Sell using WooCommerce, Etsy, Amazon and More. Click to start selling!', 'mojo-marketplace-wp-plugin' ); ?>" />
		<div>
			<a class="promo-btn-primary" href="https://ecomdash.com/bluehost/?utm_campaign=secretteam&utm_medium=dashboard-promo&utm_source=plugin_bluehost&utm_content=start-selling" target="_blank"><?php esc_html_e( 'Start selling', 'mojo-marketplace-wp-plugin' ); ?></a>
		</div>
	</div>
	<?php
}

function mm_ecomdash_hg_content() {
	?>
	<style type="text/css">
		#ecomdash-bluehost {
			position:relative;
		}
		#ecomdash-hostgator img {
			width:100%;
			height:auto;
		}
		#ecomdash-hostgator div {
			position: absolute;
			bottom: 15%;
			text-align:
			center;width: 100%;
		}
		#ecomdash-hostgator .promo-btn-primary,
		#ecomdash-hostgator .promo-btn-secondary:hover {
			background-color: #fff;
			color: #0D3150;
			padding: 10px 20px;
			border-radius:4px;
			text-decoration: none;
			font-size: 16px;
			margin: 2px;
			border: 1px solid #fff;
		}
		#ecomdash-hostgator .promo-btn-secondary,
		#ecomdash-hostgator .promo-btn-primary:hover {
			background-color: #0D3150;
			color: #fff;
			padding: 10px 20px;
			border-radius:4px;
			text-decoration: none;
			font-size: 16px;
			border: 1px solid #fff;
			margin:2px;
		}
	</style>
	<div id="ecomdash-hostgator">
		<img src="https://mojomarketplace.com/mojo-plugin-assets/img/ecomdash-hostgator.png" alt="<?php esc_attr_e( 'Ecomdash and HostGator. Ecomdash brings all your data together so you can sell everywhere, and manage it all from one easy place. Sell using WooCommerce, Etsy, Amazon and More. Click to start selling!', 'mojo-marketplace-wp-plugin' ); ?>" />
		<div>
			<a class="promo-btn-primary" href="https://ecomdash.com/hostgator/?utm_campaign=secretteam&utm_medium=dashboard-promo&utm_source=plugin_hostgator&utm_content=start-selling" target="_blank"><?php esc_html_e( 'Start selling', 'mojo-marketplace-wp-plugin' ); ?></a>
		</div>
	</div>
	<?php
}
