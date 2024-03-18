<?php
/**
 * Alpha Elementor Single Product Price Widget
 *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;

class Alpha_Single_Product_Price_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_sproduct_price';
	}

	public function get_title() {
		return esc_html__( 'Product Price', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-product-price';
	}

	public function get_categories() {
		return array( 'alpha_single_product_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'product', 'woocommerce', 'shop', 'store', 'price' );
	}

	public function get_script_depends() {
		$depends = array();
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
		}
		return $depends;
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_price',
			array(
				'label' => esc_html__( 'Style', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'sp_typo',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '{{WRAPPER}} p.price, {{WRAPPER}} .price del',
				)
			);

			$this->add_control(
				'sp_price_align',
				array(
					'label'     => esc_html__( 'Alignment', 'alpha-core' ),
					'type'      => Controls_Manager::CHOOSE,
					'default'   => 'left',
					'options'   => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'alpha-core' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'alpha-core' ),
							'icon'  => 'eicon-text-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'alpha-core' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'selectors' => array(
						'{{WRAPPER}}' => 'text-align: {{VALUE}};',
					),
					'toggle'    => false,
				)
			);

			$this->add_control(
				'sp_normal_color',
				array(
					'label'     => esc_html__( 'Price Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} p.price' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'sp_new_color',
				array(
					'label'     => esc_html__( 'New Price Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ins' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'sp_old_color',
				array(
					'label'     => esc_html__( 'Old Price Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} del' => 'color: {{VALUE}};',
					),
				)
			);
	}

	protected function render() {
		/**
		 * Filters post products in single product builder
		 *
		 * @since 1.0
		 */
		if ( apply_filters( 'alpha_single_product_builder_set_preview', false ) ) {
			woocommerce_template_single_price();
			do_action( 'alpha_single_product_builder_unset_preview' );
		}
	}
}
