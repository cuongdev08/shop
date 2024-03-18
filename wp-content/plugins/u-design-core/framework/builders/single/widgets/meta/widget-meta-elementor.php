<?php
/**
 * Alpha Elementor Single Post Meta Widget
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

class Alpha_Single_Meta_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_single_meta';
	}

	public function get_title() {
		return esc_html__( 'Post Meta', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-post-info';
	}

	public function get_categories() {
		return array( 'alpha_single_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'post', 'meta' );
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
			'section_single_meta',
			array(
				'label' => esc_html__( 'Style', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'sb_align',
				array(
					'label'     => esc_html__( 'Align', 'alpha-core' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'flex-start' => array(
							'title' => esc_html__( 'Left', 'alpha-core' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center'     => array(
							'title' => esc_html__( 'Center', 'alpha-core' ),
							'icon'  => 'eicon-text-align-center',
						),
						'flex-end'   => array(
							'title' => esc_html__( 'Right', 'alpha-core' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .post-meta' => 'justify-content: {{VALUE}}',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'link_typography',
					'selector' => '.elementor-element-{{ID}} .post-meta',
				)
			);

			$this->add_control(
				'link_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .post-meta' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'link_hover_color',
				array(
					'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} a:hover,.elementor-element-{{ID}} a:focus' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'mark_color',
				array(
					'label'     => esc_html__( 'Comment Count Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .post-meta mark' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'mark_hover_color',
				array(
					'label'     => esc_html__( 'Comment Count Hover Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .comments-link:hover mark' => 'color: {{VALUE}}',
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		/**
		 * Filters the preview for editor and template.
		 *
		 * @since 1.0
		 */
		if ( apply_filters( 'alpha_single_builder_set_preview', false ) ) {
			alpha_get_template_part( 'posts/loop/post', 'meta' );
			do_action( 'alpha_single_builder_unset_preview' );
		}
	}
}
