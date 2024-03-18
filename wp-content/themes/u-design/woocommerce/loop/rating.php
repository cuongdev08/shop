<?php
/**
 * Loop Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/rating.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || die;

if ( ! wc_review_ratings_enabled() ) {
	return;
}

$type = alpha_wc_get_loop_prop( 'product_type' );

if ( $type && ! in_array( $type, array( 'product-1', 'product-2', 'product-4', 'widget' ) ) ) {
	return;
}

if ( 'widget' != $type ^ empty( $is_widget_type ) ) {
	return;
}

global $product;
?>
<div class="woocommerce-product-rating">
	<?php
	if ( apply_filters( 'alpha_single_product_rating_show_number', false ) ) {
		echo esc_html( $product->get_average_rating() );
	} else {
		echo wc_get_rating_html( $product->get_average_rating() );
	}

	if ( apply_filters( 'alpha_single_product_show_review', comments_open() && 'widget' != $type ) ) {
		echo alpha_get_rating_link_html( $product );
	}
	?>
</div>
