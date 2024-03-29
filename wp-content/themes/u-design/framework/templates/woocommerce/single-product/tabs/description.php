<?php
/**
 * Description tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

global $post;

$heading = ! empty( alpha_get_option( 'product_description_title' ) ) ? alpha_get_option( 'product_description_title' ) : apply_filters( 'woocommerce_product_description_heading', __( 'Description', 'alpha' ) );
?>

<?php if ( $heading ) : ?>
	<?php
	/**
	 * Filters tab type in single product page.
	 *
	 * @since 1.0
	 */
	if ( 'section' == apply_filters( 'alpha_single_product_data_tab_type', 'tab' ) ) :
		?>
		<h2 class="title-wrapper title-underline">
			<span class="title"><?php echo esc_html( $heading ); ?></span>
		</h2>
	<?php else : ?>
		<h2><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>
<?php endif; ?>

<?php the_content(); ?>
