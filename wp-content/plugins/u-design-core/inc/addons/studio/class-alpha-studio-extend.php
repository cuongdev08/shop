<?php
/**
 * Alpha Studio Extend
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
* @since      4.1
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Studio_Extend' ) ) :

	/**
	 * The Alpha Studio class
	 *
	 * @since 1.0
	 */
	class Alpha_Studio_Extend {

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			// Extend Block Categories
			add_filter( 'alpha_studio_category', array( $this, 'extend_studio_categories' ) );
			add_filter( 'alpha_studio_big_category', array( $this, 'extend_studio_big_categories' ) );
			add_filter( 'alpha_studio_block_category', array( $this, 'extend_studio_block_categories' ) );

			if ( 'post.php' == $GLOBALS['pagenow'] || 'post-new.php' == $GLOBALS['pagenow'] ) {
				if ( defined( 'ELEMENTOR_VERSION' ) && function_exists( 'alpha_is_elementor_preview' ) && alpha_is_elementor_preview() ) {
					add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue' ), 30 );
				} elseif ( defined( 'WPB_VC_VERSION' ) && function_exists( 'alpha_is_wpb_preview' ) && alpha_is_wpb_preview() ) {
					add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 1001 );
				} elseif ( 'post.php' != $GLOBALS['pagenow'] || 'edit' == $_REQUEST['action'] ) {
					add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 1001 );
				}
			} elseif ( ! wp_doing_ajax() || ! isset( $_POST['type'] ) ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 1001 );
			}
		}

		public function enqueue() {
			wp_enqueue_style( 'alpha-studio-extend', ALPHA_CORE_URI . '/inc/addons/studio/studio-extend' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' );
			wp_enqueue_script( 'alpha-studio-extend', ALPHA_CORE_URI . '/inc/addons/studio/studio-extend' . ALPHA_JS_SUFFIX, array( 'alpha-studio' ), ALPHA_CORE_VERSION, true );
		}

		public function extend_studio_big_categories( $categories ) {
			return array( 'header', 'page_title_bar', 'block', 'footer', 'popup', 'template', 'favourites', 'my-templates' );
		}

		public function extend_studio_categories( $categories ) {
			$categories = array_merge(
				$categories,
				array(
					'page_title_bar' => esc_html__( 'Page Title Bar', 'alpha-core' ),
					'products'       => esc_html__( 'Products', 'alpha-core' ),
					'posts'          => esc_html__( 'Posts', 'alpha-core' ),
					'projects'       => esc_html__( 'Projects', 'alpha-core' ),
					'team'           => esc_html__( 'Team', 'alpha-core' ),
				)
			);
			return $categories;
		}

		public function extend_studio_block_categories( $categories ) {
			$categories = array_merge(
				$categories,
				array(
					'products',
					'posts',
					'projects',
					'team',
				)
			);
			return $categories;
		}
	}

	new Alpha_Studio_Extend;

endif;
