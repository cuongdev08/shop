<?php
/**
 * Alpha Product Category Banner Functions
 *
 * Functions used to display banner type.
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.1
 */

// Category Thumbnail
add_action( 'woocommerce_before_subcategory_title', 'alpha_pc_banner_before_subcategory_thumbnail', 5 );
add_action( 'woocommerce_before_subcategory_title', 'alpha_pc_banner_after_subcategory_thumbnail', 15 );
add_action( 'woocommerce_before_subcategory_title', 'alpha_pc_banner_subcategory_thumbnail' );

// Category Content
add_action( 'woocommerce_after_subcategory_title', 'alpha_pc_banner_after_subcategory_title' );
add_action( 'woocommerce_shop_loop_subcategory_title', 'alpha_pc_banner_template_loop_category_title' );

/**
 * pc_banner_before_subcategory_thumbnail
 *
 * Render html after subcategory thumbnail.
 *
 * @param string $category
 * @since 4.1
 */
if ( ! function_exists( 'alpha_pc_banner_before_subcategory_thumbnail' ) ) {
	function alpha_pc_banner_before_subcategory_thumbnail( $category ) {
		if ( 'banner' != alpha_wc_get_loop_prop( 'category_type' ) ) {
			return;
		}
		echo '<a href="' . esc_url( get_term_link( $category, 'product_cat' ) ) . '"' .
			( alpha_wc_get_loop_prop( 'run_as_filter' ) ? ' data-cat="' . $category->term_id . '"' : '' ) . '>';
		echo '<figure>';
	}
}

/**
 * pc_banner_subcategory_thumbnail
 *
 * Render subcategory thumbnail.
 *
 * @param string $category
 * @since 4.1
 */
if ( ! function_exists( 'alpha_pc_banner_subcategory_thumbnail' ) ) {
	function alpha_pc_banner_subcategory_thumbnail( $category ) {
		if ( 'banner' != alpha_wc_get_loop_prop( 'category_type' ) ) {
			return;
		}

		if ( alpha_wc_get_loop_prop( 'show_icon', false ) ) {
			$icon_class = get_term_meta( $category->term_id, 'product_cat_icon', true );
			$icon_class = $icon_class ? $icon_class : 'far fa-heart';
			echo '<i class="' . esc_attr( $icon_class ) . '"></i>';
		} else {

			$html           = '';
			$thumbnail_size = apply_filters( 'subcategory_archive_thumbnail_size', 'woocommerce_thumbnail' );
			if ( isset( $GLOBALS['alpha_current_cat_img_size'] ) ) {
				$thumbnail_size = $GLOBALS['alpha_current_cat_img_size'];
				unset( $GLOBALS['alpha_current_cat_img_size'] );
			}
			$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
			$dimensions   = false;

			if ( ! in_array( str_replace( 'woocommerce_', '', $thumbnail_size ), array( 'shop_single', 'single', 'shop_catalog', 'thumbnail', 'shop_thumbnail', 'gallery_thumbnail' ) ) ) {
				if ( 'full' == $thumbnail_size ) {
					$dimensions = wp_get_attachment_metadata( $thumbnail_id );
				} else {
					$dimensions = image_get_intermediate_size( $thumbnail_id, array( $thumbnail_size ) );
				}
			}
			if ( ! $dimensions ) {
				$dimensions = wc_get_image_size( $thumbnail_size );
			}

			if ( $thumbnail_id ) {
				if ( isset( $dimensions['url'] ) && $dimensions['url'] ) {
					$image = $dimensions['url'];
				} else {
					$image = isset( wp_get_attachment_image_src( $thumbnail_id, $thumbnail_size )[0] ) ? wp_get_attachment_image_src( $thumbnail_id, $thumbnail_size )[0] : '';
				}
				$image_srcset = wp_get_attachment_image_srcset( $thumbnail_id, $thumbnail_size );
				$image_meta   = wp_get_attachment_metadata( $thumbnail_id );
				$image_sizes  = wp_get_attachment_image_sizes( $thumbnail_id, $thumbnail_size, $image_meta );

				if ( 0 == $dimensions['height'] ) {
					$full_image_size = wp_get_attachment_image_src( $thumbnail_id, 'full' );
					if ( isset( $full_image_size[1] ) && $full_image_size[1] ) {
						$dimensions['height'] = intval( $dimensions['width'] / absint( $full_image_size[1] ) * absint( $full_image_size[2] ) );
					}
				}

				// If image's width is smaller than thumbnail size, use real image's size.
				if ( is_array( $dimensions ) && is_array( $image_meta ) && $dimensions['width'] > $image_meta['width'] ) {
					$dimensions['width']  = $image_meta['width'];
					$dimensions['height'] = $image_meta['height'];
				}
			} else {
				$image        = wc_placeholder_img_src();
				$image_srcset = false;
				$image_sizes  = false;
			}

			if ( $image ) {
				// Prevent esc_url from breaking spaces in urls for image embeds.
				// Ref: https://core.trac.wordpress.org/ticket/23605.
				$image = str_replace( ' ', '%20', $image );

				// Add responsive image markup if available.
				if ( $image_srcset && $image_sizes ) {
					$html = '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" srcset="' . esc_attr( $image_srcset ) . '" sizes="' . esc_attr( $image_sizes ) . '" />';
				} else {
					$html = '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />';
				}
			}

			echo apply_filters( 'alpha_wc_subcategory_thumbnail_html', $html );
		}
	}
}

/**
 * pc_banner_after_subcategory_thumbnail
 *
 * Render html after subcategory thumbnail.
 *
 * @param string $category
 * @since 4.1
 */
if ( ! function_exists( 'alpha_pc_banner_after_subcategory_thumbnail' ) ) {
	function alpha_pc_banner_after_subcategory_thumbnail( $category ) {
		if ( 'banner' != alpha_wc_get_loop_prop( 'category_type' ) ) {
			return;
		}
		$content_origin = alpha_wc_get_loop_prop( 'content_origin' );
		echo '</figure>';
		echo '</a>';
		if ( $content_origin ) {
			echo '<div class="category-content ' . esc_attr( $content_origin ) . '">';
		} else {
			echo '<div class="category-content">';
		}
	}
}

/**
 * pc_banner_template_loop_category_title
 *
 * Render product category title.
 *
 * @param array $category
 * @since 4.1
 */
if ( ! function_exists( 'alpha_pc_banner_template_loop_category_title' ) ) {
	function alpha_pc_banner_template_loop_category_title( $category ) {
		if ( 'banner' != alpha_wc_get_loop_prop( 'category_type' ) ) {
			return;
		}

		// Title
		echo '<h3 class="woocommerce-loop-category__title">' . esc_html( $category->name ) . '</h3>';

		// Count
		if ( alpha_wc_get_loop_prop( 'show_count', true ) ) {
			echo apply_filters( 'woocommerce_subcategory_count_html', '<mark>' . esc_html( $category->count ) . ' ' . esc_html__( 'Products', 'alpha' ) . '</mark>', $category );
		}
		// Link
		if ( alpha_wc_get_loop_prop( 'show_link', true ) ) {
			$link_text  = alpha_wc_get_loop_prop( 'link_text' );
			$link_class = 'btn btn-underline btn-link';
			echo '<a class="' . esc_html( $link_class ) . '"' .
				( alpha_wc_get_loop_prop( 'run_as_filter' ) ? ' data-cat="' . $category->term_id . '"' : '' ) .
				' href="' . esc_url( get_term_link( $category, 'product_cat' ) ) . '">' .
				( $link_text ? esc_html( $link_text ) : esc_html__( 'Shop Now', 'alpha' ) ) .
				'<i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-right"></i>' . '</a>';
		}
	}
}

/**
 * pc_banner_after_subcategory_title
 *
 * Render html after subcategory title.
 *
 * @since 4.1
 */
if ( ! function_exists( 'alpha_pc_banner_after_subcategory_title' ) ) {
	function alpha_pc_banner_after_subcategory_title() {
		if ( 'banner' == alpha_wc_get_loop_prop( 'category_type' ) ) {
			echo '</div>';
		}
	}
}
