<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce\Templates
 * @version     3.0.0
 */

defined( 'ABSPATH' ) || die;

if ( $upsells ) :
	global $product;
	// get all categories that product belongs to...
	$categories = $product->get_category_ids(); ?>

	<section class="up-sells upsells products">
		<?php
		$title = alpha_get_option( 'product_upsells_title' );
		if ( ! $title ) {
			$title = esc_html__( 'You may also like&hellip;', 'alpha' );
		}
		$heading = apply_filters( 'woocommerce_product_upsells_products_heading', $title );

		if ( $heading ) :
			?>
			<h2 class="title title-link"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<div>

			<?php woocommerce_product_loop_start(); ?>

				<?php foreach ( $upsells as $upsell ) : ?>

					<?php
					$post_object = get_post( $upsell->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

					wc_get_template_part( 'content', 'product' );
					?>

				<?php endforeach; ?>

			<?php woocommerce_product_loop_end(); ?>

		</div>
	</section>

	<?php
endif;

wp_reset_postdata();
