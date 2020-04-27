<?php
/*
This file creates the widget for themes
*/

class MOJO_Widget extends WP_Widget {
	var $defaults = array(
		'mojo-title'      => 'Mojo Marketplace',
		'mojo-platform'   => 'wordpress',
		'mojo-type'       => 'themes',
		'mojo-items'      => 'recent',
		'mojo-image-size' => 'thumbnail',
		'mojo-quantity'   => '3',
		'mojo-preview'    => 'off',
		'mojo-seller'     => '',
		'mojo-aff-id'     => '',
	);
	public function __construct() {
		$this->defaults['mojo-title'] = __( 'Mojo Marketplace', 'mojo-marketplace-wp-plugin' );

		parent::__construct(
			'mojo_widget',
			__( 'MOJO WordPress Themes', 'mojo-marketplace-wp-plugin' ),
			array( 'description' => __( 'Add Themes/Plugins from MOJO.', 'mojo-marketplace-wp-plugin' ) )
		);
	}
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );
		?>
		<label for="<?php echo $this->get_field_name( 'mojo-title' ); ?>"><?php esc_html_e( 'Title', 'mojo-marketplace-wp-plugin' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'mojo-title' ); ?>" name="<?php echo $this->get_field_name( 'mojo-title' ); ?>" type="text" value="<?php echo esc_attr( $instance['mojo-title'] ); ?>" />

		<label for="<?php echo $this->get_field_name( 'mojo-platform' ); ?>"><?php esc_html_e( 'Platform', 'mojo-marketplace-wp-plugin' ); ?>:</label>
		<select  class="widefat mojo-wid-type" id="<?php echo $this->get_field_id( 'mojo-platform' ); ?>" name="<?php echo $this->get_field_name( 'mojo-platform' ); ?>">
			<option value='wordpress' <?php selected( $instance['mojo-platform'], 'WordPress', true ); ?>><?php esc_html_e( 'WordPress', 'mojo-marketplace-wp-plugin' ); ?></option>
			<option value='joomla' <?php selected( $instance['mojo-platform'], 'joomla', true ); ?>><?php esc_html_e( 'Joomla', 'mojo-marketplace-wp-plugin' ); ?></option>
			<option value='drupal' <?php selected( $instance['mojo-platform'], 'drupal', true ); ?>><?php esc_html_e( 'Drupal', 'mojo-marketplace-wp-plugin' ); ?></option>
			<option value='magento' <?php selected( $instance['mojo-platform'], 'magento', true ); ?>><?php esc_html_e( 'Magento', 'mojo-marketplace-wp-plugin' ); ?></option>
			<option value='prestashop' <?php selected( $instance['mojo-platform'], 'prestashop', true ); ?>><?php esc_html_e( 'PrestaShop', 'mojo-marketplace-wp-plugin' ); ?></option>
		</select>

		<label for="<?php echo $this->get_field_name( 'mojo-type' ); ?>">Type:</label>
		<select  class="widefat mojo-wid-type" id="<?php echo $this->get_field_id( 'mojo-type' ); ?>" name="<?php echo $this->get_field_name( 'mojo-type' ); ?>">
			<option value='themes' <?php selected( $instance['mojo-type'], 'themes', true ); ?>><?php esc_html_e( 'Themes', 'mojo-marketplace-wp-plugin' ); ?></option>
			<option value='plugins' <?php selected( $instance['mojo-type'], 'plugins', true ); ?>><?php esc_html_e( 'Plugins', 'mojo-marketplace-wp-plugin' ); ?></option>
		</select>

		<label for="<?php echo $this->get_field_name( 'mojo-items' ); ?>">Items:</label>
		<select  class="widefat mojo-wid-type" id="<?php echo $this->get_field_id( 'mojo-items' ); ?>" name="<?php echo $this->get_field_name( 'mojo-items' ); ?>">
			<option value='popular' <?php selected( $instance['mojo-items'], 'popular', true ); ?>><?php esc_html_e( 'Popular', 'mojo-marketplace-wp-plugin' ); ?></option>
			<option value='recent' <?php selected( $instance['mojo-items'], 'recent', true ); ?>><?php esc_html_e( 'Recent', 'mojo-marketplace-wp-plugin' ); ?></option>
		</select>

		<label for="<?php echo $this->get_field_name( 'mojo-image-size' ); ?>">Image Size:</label>
		<select  class="widefat mojo-wid-type" id="<?php echo $this->get_field_id( 'mojo-image-size' ); ?>" name="<?php echo $this->get_field_name( 'mojo-image-size' ); ?>">
			<option value='square_thumbnail_url' <?php selected( $instance['mojo-image-size'], 'square_thumbnail_url', true ); ?>><?php esc_html_e( 'Square Thumbnail', 'mojo-marketplace-wp-plugin' ); ?></option>
			<option value='thumbnail_url' <?php selected( $instance['mojo-image-size'], 'thumbnail_url', true ); ?>><?php esc_html_e( 'Thumbnail', 'mojo-marketplace-wp-plugin' ); ?></option>
			<option value='large_thumbnail_url' <?php selected( $instance['mojo-image-size'], 'large_thumbnail_url', true ); ?>><?php esc_html_e( 'Large', 'mojo-marketplace-wp-plugin' ); ?></option>
		</select>

		<label for="<?php echo $this->get_field_name( 'mojo-seller' ); ?>"><?php esc_html_e( 'Seller Profile <small>(optional)</small>', 'mojo-marketplace-wp-plugin' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'mojo-seller' ); ?>" name="<?php echo $this->get_field_name( 'mojo-seller' ); ?>" type="text" value="<?php echo esc_attr( $instance['mojo-seller'] ); ?>" />

		<label for="<?php echo $this->get_field_name( 'mojo-preview' ); ?>"><?php esc_html_e( 'Preview on hover', 'mojo-marketplace-wp-plugin' ); ?>:</label>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'mojo-preview' ); ?>" name="<?php echo $this->get_field_name( 'mojo-preview' ); ?>" <?php checked( $instance['mojo-preview'], 'on', true ); ?>/>

		<label for="<?php echo $this->get_field_name( 'mojo-quantity' ); ?>"><?php esc_html_e( 'Quantity', 'mojo-marketplace-wp-plugin' ); ?>:</label>
		<select id="<?php echo $this->get_field_id( 'mojo-quantity' ); ?>" name="<?php echo $this->get_field_name( 'mojo-quantity' ); ?>">
			<?php
			for ( $i = 1; $i <= 10; $i++ ) {
				?>
			<option value='<?php echo $i; ?>' <?php selected( $instance['mojo-quantity'], $i, true ); ?>><?php echo $i; ?></option>
				<?php
			}
			?>
		</select>
		<br/>

		<?php
		if ( defined( 'MMAFF' ) && $instance['mojo-aff-id'] == MMAFF ) {
			$instance['mojo-aff-id'] = '';
		}
		?>

		<label for="<?php echo $this->get_field_name( 'mojo-aff-id' ); ?>"><?php esc_html_e( 'Affiliate ID', 'mojo-marketplace-wp-plugin' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'mojo-aff-id' ); ?>" name="<?php echo $this->get_field_name( 'mojo-aff-id' ); ?>" type="text" value="<?php echo esc_attr( $instance['mojo-aff-id'] ); ?>" />

		</p>
		<?php
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );
		$query    = array();
		if ( $instance['mojo-platform'] == 'WordPress' && $instance['mojo-type'] == 'plugins' ) {
			$instance['mojo-type'] = 'themes'; // Because MOJO Cannot sell WP plugins...
		}
		if ( $instance['mojo-quantity'] != 10 ) {
			$query['count'] = $instance['mojo-quantity'];
		}
		if ( 2 < strlen( $instance['mojo-seller'] ) ) {
			$query['seller'] = $instance['mojo-seller'];
		}

		if ( 'on' == $instance['mojo-preview'] ) {
			global $use_mm_styles;
			$use_mm_styles = true;
		}

		$items = mm_api( $instance, $query );
		/*if there are no popular items show default*/
		if ( strlen( $items['body'] ) < $instance['mojo-quantity'] and $instance['mojo-items'] == 'popular' ) {
			$items = mm_api();
		}

		if ( ! is_wp_error( $items ) ) {

			$items   = json_decode( $items['body'] );
			$aff_id  = ( isset( $instance['mojo-aff-id'] ) and strlen( $instance['mojo-aff-id'] ) > 0 ) ? $instance['mojo-aff-id'] : '';
			$content = '';
			$count   = 0;
			foreach ( $items as $item ) {
				$item->name = apply_filters( 'mm_item_name', $item->name );
				$content   .= '<div class="mojo-widget-item wp-caption" style="margin:15px 0px;">';
				$content   .= '<a target="_blank" href="' . mm_build_link(
					$item->page_url,
					array(
						'r'           => $aff_id,
						'utm_medium'  => 'plugin_widget',
						'utm_content' => 'item_thumbnail',
					)
				) . '"><img style="display:block;margin: 0 auto;max-width: 100%;" src="' . $item->images->{$instance['mojo-image-size']} . '"  /></a>';
				if ( 'on' == $instance['mojo-preview'] ) {
					$content .= '<a target="_blank" class="mojo-widget-preview" href="' . mm_build_link(
						$item->page_url,
						array(
							'r'          => $aff_id,
							'utm_medium' => 'plugin_widget',
							'utm'        => 'item_thumbnail_hover_preview',
						)
					) . '"><img src="' . $item->images->preview_url . '" /></a>';
				}
				$content .= '<p class="wp-caption-text">' . $item->name . '</p>';
				$content .= '</div>';
				$count++;
			}
			$title = apply_filters( 'widget_title', $instance['mojo-title'] );
			echo $args['before_widget'];
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			echo $content;
			echo $args['after_widget'];
		}
	}
}

function mm_register_widget() {
	register_widget( 'Mojo_Widget' );
}
add_action( 'widgets_init', 'mm_register_widget' );
