<?php
/**
 * Alpha WooCommerce Vertical Single Product Functions
 *
 * Functions used to display vertical single product type.
 *
 * @author     D-THEMES
 * @package    WP Alpha
 * @subpackage Theme
 * @since      1.0
 */

// Single Product Media
add_filter( 'alpha_product_label_group_class', 'alpha_sp_vertical_label_group_class' );
add_action( 'alpha_woocommerce_product_images', 'alpha_sp_vertical_images' );
add_action( 'woocommerce_product_thumbnails', 'alpha_wc_show_sp_vertical_thumbnails', 20 );
add_filter( 'woocommerce_get_image_size_gallery_thumbnail', 'alpha_wc_sp_vertical_thumbnail_image_size' );

/**
 * sp_vertical_label_group_class
 *
 * Return single product vertical label group class
 *
 * @param string $class
 * @return string
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_sp_vertical_label_group_class' ) ) {
	function alpha_sp_vertical_label_group_class( $class ) {
		if ( 'vertical' == alpha_get_single_product_layout() ) {
			if ( alpha_doing_quickview() || ( alpha_is_product() && ! alpha_wc_get_loop_prop( 'name' ) ) ) {
				$class .= ' pg-vertical-label';
			}
		}
		return $class;
	}
}
/**
 * sp_vertical_images
 *
 * Render vertical single product images.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_sp_vertical_images' ) ) {
	function alpha_sp_vertical_images() {
		if ( 'vertical' == alpha_get_single_product_layout() ) {
			global $product;
			global $alpha_layout;

			$post_thumbnail_id = $product->get_image_id();
			$attachment_ids    = $product->get_gallery_image_ids();

			if ( $post_thumbnail_id ) {
				$html = apply_filters( 'woocommerce_single_product_image_thumbnail_html', alpha_wc_get_gallery_image_html( $post_thumbnail_id, true, true ), $post_thumbnail_id );
			} else {
				$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
				$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image">', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'alpha' ) );
				$html .= '</div>';
			}

			if ( $attachment_ids && $post_thumbnail_id ) {
				foreach ( $attachment_ids as $attachment_id ) {
					$html .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', alpha_wc_get_gallery_image_html( $attachment_id, true ), $attachment_id );
				}
			}
			/**
			 * Filters the class(attr) of single product gallery.
			 *
			 * @since 1.0
			 */
			$html = '<div class="product-single-carousel-wrap slider-nav-fade"><div class="product-single-carousel slider-wrapper' . apply_filters( 'alpha_single_product_gallery_class', ' row cols-1 gutter-no' ) . '"' . apply_filters( 'alpha_single_product_gallery_attr', '' ) . '>' . $html . '</div></div>';

			echo alpha_escaped( $html );
		}
	}
}

/**
 * wc_show_sp_vertical_thumbnails
 *
 * Render vertical single product thumbnails
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_wc_show_sp_vertical_thumbnails' ) ) {
	function alpha_wc_show_sp_vertical_thumbnails() {
		if ( 'vertical' == alpha_get_single_product_layout() ) {
			?>
				<div class="product-thumbs-wrap<?php echo apply_filters( 'alpha_single_product_thumbs_wrap_class', '' ); ?>">
					<div class="product-thumbs slider-wrapper<?php echo apply_filters( 'alpha_single_product_thumbs_class', '' ); ?>"<?php echo apply_filters( 'alpha_single_product_thumbs_attr', '' ); ?>>
						<?php woocommerce_show_product_thumbnails(); ?>
					</div>
				</div>
			<?php
		}
	}
}
/**
 * wc_sp_vertical_thumbnail_image_size
 *
 * Return vertical single product thumbnail image size
 *
 * @param array $size
 * @return array
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_wc_sp_vertical_thumbnail_image_size' ) ) {
	function alpha_wc_sp_vertical_thumbnail_image_size( $size ) {
		if ( 'vertical' == alpha_get_single_product_layout() ) {
			$size['width']  = 150;
			$size['height'] = 150;
		}
		return $size;
	}
}
