<?php
/**
 * Alpha Cart Builder
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */
defined( 'ABSPATH' ) || die;

define( 'ALPHA_CART_BUILDER', ALPHA_BUILDERS . '/cart' );

class Alpha_Cart_Builder extends Alpha_Base {
	/**
	 * Widgets
	 *
	 * @access protected
	 * @var array[string] $widgets
	 * @since 1.2.0
	 */
	protected $widgets = array();

	/**
	 * The Constructor
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		$this->widgets = apply_filters(
			'alpha_cart_widget',
			array(
				'coupons'         => true,
				'shipping'        => true,
				'table'           => true,
				'totals'          => true,
				'linked_products' => true,
			)
		);
		add_filter( 'alpha_run_cart_builder', array( $this, 'run_template' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 25 );

		// Add controls
		add_filter( 'alpha_layout_get_controls', array( $this, 'add_layout_builder_control' ) );
		add_filter( 'alpha_layout_builder_display_parts', array( $this, 'add_layout_builder_display_parts' ) );
		add_filter( 'alpha_layout_builder_block_parts', array( $this, 'add_layout_builder_block_parts' ) );

		// @start feature: fs_pb_elementor
		if ( alpha_get_feature( 'fs_pb_elementor' ) && defined( 'ELEMENTOR_VERSION' ) ) {
			add_action( 'elementor/elements/categories_registered', array( $this, 'register_elementor_category' ) );
			add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );
		}
		// @end feature: fs_pb_elementor
	}

	/**
	 * Run builder template
	 *
	 * @since 1.2.0
	 * @access public
	 * @param boolean $run
	 * @return boolean $run
	 */
	public function run_template( $run ) {

		global $post;
		if ( $post && ALPHA_NAME . '_template' == $post->post_type && 'cart' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) {
			the_content();
			return true;

		} else {
			global $alpha_layout;

			if ( ! empty( $alpha_layout['cart_block'] ) ) {
				if ( is_numeric( $alpha_layout['cart_block'] ) ) {
					$template = (int) $alpha_layout['cart_block'];
					do_action( 'alpha_before_cart_template', $template );
					alpha_print_template( $template );
					do_action( 'alpha_after_cart_template', $template );

					return true;
				} elseif ( 'hide' == $alpha_layout['cart_block'] ) {
					return true;
				}
			}
		}

		return $run;
	}

	/**
	 * Enqueue scripts
	 *
	 * @since 1.2.0
	 */
	public function enqueue_scripts() {
		global $post;

		if ( ! empty( $post ) && ALPHA_NAME . '_template' == $post->post_type && 'cart' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) {
			wp_enqueue_style( 'alpha-theme-shop-other' );
		}
	}

	/**
	 * Add cart content template control for layout builder.
	 *
	 * @see alpha_layout_builder_controls
	 * @since 1.0.0
	 * @access public
	 * @param array $controls
	 * @return array $controls
	 */
	public function add_layout_builder_control( $controls ) {

		$controls['content_cart'] = array(
			'cart_block' => array(
				'type'  => 'block_cart',
				'label' => esc_html__( 'Cart Layout', 'alpha-core' ),
			),
		);

		return $controls;
	}

	/**
	 * Add cart content template display parts for layout builder.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @see alpha_layout_builder_display_parts
	 * @param array $controls
	 * @return array $controls
	 */
	public function add_layout_builder_display_parts( $slugs ) {

		$slugs['cart_block'] = array(
			'name'   => esc_html__( 'Cart Layout', 'alpha-core' ),
			'parent' => 'content_cart',
		);

		return $slugs;
	}

	/**
	 * Add cart template block part for layout builder.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @see alpha_layout_builder_display_parts
	 * @param array $controls
	 * @return array $controls
	 */
	public function add_layout_builder_block_parts( $blocks ) {
		$blocks[] = 'cart_block';
		return $blocks;
	}

	/**
	 * Register elementor category.
	 *
	 * @since 1.2.0
	 */
	public function register_elementor_category( $self ) {
		global $post, $alpha_layout;

		$register = false;

		if ( is_admin() ) {
			if ( ! alpha_is_elementor_preview() || ( $post && ALPHA_NAME . '_template' == $post->post_type && 'cart' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) ) {
				$register = true;
			}
		} else {
			if ( ! empty( $alpha_layout['cart'] ) && 'hide' != $alpha_layout['cart'] ) {
				$register = true;
			}
		}

		if ( $register ) {
			$self->add_category(
				'alpha_cart_widget',
				array(
					'title'  => ALPHA_DISPLAY_NAME . esc_html__( ' Cart', 'alpha-core' ),
					'active' => true,
				)
			);
		}
	}

	/**
	 * Register elementor widgets.
	 *
	 * @since 1.2.0
	 */
	public function register_elementor_widgets( $self ) {
		global $post, $alpha_layout;

		$register = $post && ALPHA_NAME . '_template' == $post->post_type && 'cart' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true );

		if ( ! $register ) {
			global $alpha_layout;
			$register = ! empty( $alpha_layout['cart_block'] ) && is_numeric( $alpha_layout['cart_block'] );
		}

		if ( $register ) {
			foreach ( $this->widgets as $widget => $usable ) {
				if ( $usable ) {
					if ( 'linked_products' == $widget ) {
						require_once alpha_core_framework_path( ALPHA_BUILDERS . '/single-product/widgets/' . str_replace( '_', '-', $widget ) . '/widget-' . str_replace( '_', '-', $widget ) . '-elementor.php' );
						$class_name = 'Alpha_Product_' . ucwords( $widget, '_' ) . '_Elementor_Widget';
					} else {
						require_once alpha_core_framework_path( ALPHA_BUILDERS . '/cart/widgets/' . str_replace( '_', '-', $widget ) . '/widget-cart-' . str_replace( '_', '-', $widget ) . '-elementor.php' );
						$class_name = 'Alpha_Cart_' . ucwords( $widget, '_' ) . '_Elementor_Widget';
					}
					$self->register( new $class_name( array(), array( 'widget_name' => $class_name ) ) );
				}
			}
		}
	}
}
Alpha_Cart_Builder::get_instance();
