<?php
/**
 * Timeline Horizontal Element
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

class Alpha_Timeline_Horizontal_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_timeline_horizontal';
	}

	public function get_title() {
		return esc_html__( 'Timeline - Horizontal', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-time-line alpha-time-line-horizontal';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'timeline', 'process', 'step', 'horizontal' );
	}

	/**
	 * Get Script depends.
	 *
	 * @since 1.2.0
	 */
	public function get_script_depends() {
		wp_register_script( 'alpha-timeline-horizontal', alpha_core_framework_uri( '/widgets/timeline-horizontal/timeline-horizontal' . ALPHA_JS_SUFFIX ), array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
		return array( 'alpha-timeline-horizontal' );
	}

	/**
	 * Get Style depends.
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-timeline', alpha_core_framework_uri( '/widgets/timeline/timeline' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		wp_register_style( 'alpha-timeline-horizontal', alpha_core_framework_uri( '/widgets/timeline-horizontal/timeline-horizontal' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array( 'alpha-timeline' ), ALPHA_CORE_VERSION );
		return array( 'alpha-timeline', 'alpha-timeline-horizontal' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_timeline_content',
			array(
				'label' => esc_html__( 'Content', 'alpha-core' ),
			)
		);
			$repeater = new Repeater();

			$repeater->add_control(
				'active_item',
				array(
					'label' => esc_html__( 'Active', 'alpha-core' ),
					'type'  => Controls_Manager::SWITCHER,
				)
			);

			$repeater->add_control(
				'show_image',
				array(
					'label'       => esc_html__( 'Show Image', 'alpha-core' ),
					'description' => esc_html__( 'Choose whether to show image.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'condition'   => array(
						'show_icon!' => 'yes',
					),
				)
			);
			$repeater->add_control(
				'image_position',
				array(
					'label'       => esc_html__( 'Image Position', 'alpha-core' ),
					'description' => esc_html__( 'Choose the position of the image.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => '',
					'options'     => array(
						''             => esc_html__( 'Default', 'alpha-core' ),
						'before_title' => esc_html__( 'Before Title', 'alpha-core' ),
						'after_desc'   => esc_html__( 'After Description', 'alpha-core' ),
					),
					'condition'   => array(
						'show_image' => 'yes',
						'show_icon!' => 'yes',
					),
				)
			);
			$repeater->add_control(
				'image',
				array(
					'label'       => esc_html__( 'Image', 'alpha-core' ),
					'description' => esc_html__( 'Choose a certain image.', 'alpha-core' ),
					'type'        => Controls_Manager::MEDIA,
					'default'     => array(
						'url' => Utils::get_placeholder_image_src(),
					),
					'condition'   => array(
						'show_image' => 'yes',
						'show_icon!' => 'yes',
					),
				)
			);

			$repeater->add_group_control(
				Group_Control_Image_Size::get_type(),
				array(
					'name'      => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`
					'exclude'   => [ 'custom' ],
					'default'   => 'woocommerce_thumbnail',
					'condition' => array(
						'show_image' => 'yes',
						'show_icon!' => 'yes',
					),
				)
			);

			$repeater->add_control(
				'show_icon',
				array(
					'label'       => esc_html__( 'Show Icon', 'alpha-core' ),
					'description' => esc_html__( 'Choose whether to show icon.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'condition'   => array(
						'show_image!' => 'yes',
					),
				)
			);
			$repeater->add_control(
				'icon_position',
				array(
					'label'       => esc_html__( 'Icon Position', 'alpha-core' ),
					'description' => esc_html__( 'Choose the position of the icon.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => '',
					'options'     => array(
						''             => esc_html__( 'Default', 'alpha-core' ),
						'before_title' => esc_html__( 'Before Title', 'alpha-core' ),
						'after_desc'   => esc_html__( 'After Description', 'alpha-core' ),
					),
					'condition'   => array(
						'show_icon'   => 'yes',
						'show_image!' => 'yes',
					),
				)
			);
			$repeater->add_control(
				'timeline_icon',
				array(
					'label'       => esc_html__( 'Icon', 'alpha-core' ),
					'description' => esc_html__( 'Choose icon from icon library that will be shown.', 'alpha-core' ),
					'type'        => Controls_Manager::ICONS,
					'default'     => array(
						'value'   => ALPHA_ICON_PREFIX . '-icon-cart',
						'library' => 'alpha-icons',
					),
					'condition'   => array(
						'show_icon'   => 'yes',
						'show_image!' => 'yes',
					),
				)
			);
			$repeater->add_control(
				'timeline_item_title',
				array(
					'label'       => esc_html__( 'Title', 'alpha-core' ),
					'description' => esc_html__( 'Type a timeline title.', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
				)
			);

			$repeater->add_control(
				'meta',
				array(
					'label'       => esc_html__( 'Meta', 'alpha-core' ),
					'description' => esc_html__( 'Type a timeline meta.', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
				)
			);

			$repeater->add_control(
				'desc',
				array(
					'label'       => esc_html__( 'Description', 'alpha-core' ),
					'description' => esc_html__( 'Type a timeline description.', 'alpha-core' ),
					'type'        => Controls_Manager::TEXTAREA,
					'rows'        => '10',
				)
			);

			$repeater->add_control(
				'breakpoint_heading',
				array(
					'label'     => esc_html__( 'Breakpoint', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$repeater->add_control(
				'breakpoint_type',
				array(
					'label'       => esc_html__( 'Type', 'alpha-core' ),
					'description' => esc_html__( 'Choose the breakpoint type.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'text',
					'options'     => array(
						'icon' => esc_html__( 'Icon', 'alpha-core' ),
						'text' => esc_html__( 'Text', 'alpha-core' ),
					),
				)
			);

			$repeater->add_control(
				'breakpoint_icon',
				array(
					'label'       => esc_html__( 'Select Icon', 'alpha-core' ),
					'description' => esc_html__( 'Choose the breakpoint icon from Icon Library.', 'alpha-core' ),
					'type'        => Controls_Manager::ICONS,
					'default'     => array(
						'value'   => ALPHA_ICON_PREFIX . '-icon-cart',
						'library' => 'alpha-icons',
					),
					'condition'   => array(
						'breakpoint_type' => 'icon',
					),
				)
			);

			$repeater->add_control(
				'breakpoint_text',
				array(
					'label'       => esc_html__( 'Text', 'alpha-core' ),
					'description' => esc_html__( 'Input the breakpoint text.', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'condition'   => array(
						'breakpoint_type' => 'text',
					),
				)
			);

			$presets = array(
				array(
					'timeline_item_title' => esc_html__( 'Step #1', 'alpha-core' ),
					'meta'                => esc_html__( 'Monday, October 11, 2021', 'alpha-core' ),
					'desc'                => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'alpha-core' ),
					'breakpoint_text'     => '1',
				),
				array(
					'timeline_item_title' => esc_html__( 'Step #2', 'alpha-core' ),
					'meta'                => esc_html__( 'Tuesday, October 12, 2021', 'alpha-core' ),
					'desc'                => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'alpha-core' ),
					'breakpoint_text'     => '2',
				),
				array(
					'timeline_item_title' => esc_html__( 'Step #3', 'alpha-core' ),
					'meta'                => esc_html__( 'Wednesday, October 13, 2021', 'alpha-core' ),
					'desc'                => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'alpha-core' ),
					'breakpoint_text'     => '3',
				),
			);

			$this->add_control(
				'timeline_list',
				array(
					'label'       => esc_html__( 'Timeline Items', 'alpha-core' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'default'     => $presets,
					'title_field' => '{{{timeline_item_title}}}',
				)
			);

			$this->add_control(
				'timeline_custom_line',
				array(
					'label' => esc_html__( 'Use Custom Line', 'alpha-core' ),
					'type'  => Controls_Manager::SWITCHER,
				)
			);

			$this->add_control(
				'custom_line',
				array(
					'label'     => esc_html__( 'Choose image', 'alpha-core' ),
					'type'      => Controls_Manager::MEDIA,
					'condition' => array(
						'timeline_custom_line' => 'yes',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_timeline_layout',
			array(
				'label' => esc_html__( 'Layout', 'alpha-core' ),
			)
		);

			// $this->add_control(
			// 	'col_cnt_xl',
			// 	array(
			// 		'label'       => esc_html__( 'Columns ( >= 1200px )', 'alpha-core' ),
			// 		'description' => esc_html__( 'Select number of columns to display on large display( >= 1200px ). ', 'alpha-core' ),
			// 		'type'        => Controls_Manager::SELECT,
			// 		'options'     => array(
			// 			'1' => 1,
			// 			'2' => 2,
			// 			'3' => 3,
			// 			'4' => 4,
			// 			'5' => 5,
			// 			'6' => 6,
			// 			'7' => 7,
			// 			'8' => 8,
			// 			''  => esc_html__( 'Default', 'alpha-core' ),
			// 		),
			// 		'label_block' => true,
			// 	)
			// );

			$this->add_responsive_control(
				'col_cnt',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'Columns', 'alpha-core' ),
					'description' => esc_html__( 'Select number of columns to display.', 'alpha-core' ),
					'options'     => array(
						'1' => 1,
						'2' => 2,
						'3' => 3,
						'4' => 4,
						'5' => 5,
						'6' => 6,
						'7' => 7,
						'8' => 8,
						''  => esc_html__( 'Default', 'alpha-core' ),
					),
					'default'     => '3',
					'label_block' => true,
				)
			);

			// $this->add_control(
			// 	'col_cnt_min',
			// 	array(
			// 		'label'       => esc_html__( 'Columns ( < 576px )', 'alpha-core' ),
			// 		'description' => esc_html__( 'Select number of columns to display on mobile( < 576px ). ', 'alpha-core' ),
			// 		'type'        => Controls_Manager::SELECT,
			// 		'options'     => array(
			// 			'1' => 1,
			// 			'2' => 2,
			// 			'3' => 3,
			// 			'4' => 4,
			// 			'5' => 5,
			// 			'6' => 6,
			// 			'7' => 7,
			// 			'8' => 8,
			// 			''  => esc_html__( 'Default', 'alpha-core' ),
			// 		),
			// 		'label_block' => true,
			// 	)
			// );

			$this->add_control(
				'v_space',
				array(
					'label'       => esc_html__( 'Vertical Space', 'alpha-core' ),
					'description' => esc_html__( 'Controls the amount of spacing between timeline items.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'default'     => array(
						'size' => 50,
					),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
					),
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .timeline-item + .timeline-item' => 'margin-top: {{SIZE}}px;',
					),
				)
			);

			$this->add_control(
				'col_sp',
				array(
					'label'       => esc_html__( 'Horizontal Space', 'alpha-core' ),
					'description' => esc_html__( 'Select the amount of spacing between items.', 'alpha-core' ),
					'type'        => Controls_Manager::CHOOSE,
					'options'     => apply_filters(
						'alpha_col_sp',
						array(
							'no' => array(
								'title' => esc_html__( 'No space', 'alpha-core' ),
								'icon'  => 'eicon-ban',
							),
							'xs' => array(
								'title' => esc_html__( 'Extra Small', 'alpha-core' ),
								'icon'  => 'alpha-size-xs alpha-choose-type',
							),
							'sm' => array(
								'title' => esc_html__( 'Small', 'alpha-core' ),
								'icon'  => 'alpha-size-sm alpha-choose-type',
							),
							'md' => array(
								'title' => esc_html__( 'Medium', 'alpha-core' ),
								'icon'  => 'alpha-size-md alpha-choose-type',
							),
							'lg' => array(
								'title' => esc_html__( 'Large', 'alpha-core' ),
								'icon'  => 'alpha-size-lg alpha-choose-type',
							),
						),
						'elementor'
					),
					'label_block' => true,
				)
			);

			$this->add_control(
				'h_align',
				array(
					'label'       => esc_html__( 'Horizontal Alignment', 'alpha-core' ),
					'description' => esc_html__( 'Select the horizontal alignment.', 'alpha-core' ),
					'type'        => Controls_Manager::CHOOSE,
					'default'     => 'center',
					'options'     => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'alpha-core' ),
							'icon'  => 'eicon-h-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'alpha-core' ),
							'icon'  => 'eicon-h-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'alpha-core' ),
							'icon'  => 'eicon-h-align-right',
						),
					),
					'toggle'      => false,
				)
			);

			$this->add_control(
				'v_align',
				array(
					'label'       => esc_html__( 'Vertical Alignment', 'alpha-core' ),
					'description' => esc_html__( 'Select the vertical alignment.', 'alpha-core' ),
					'type'        => Controls_Manager::CHOOSE,
					'default'     => 'middle',
					'options'     => array(
						'top'    => array(
							'title' => esc_html__( 'Top', 'alpha-core' ),
							'icon'  => 'eicon-v-align-top',
						),
						'middle' => array(
							'title' => esc_html__( 'Middle', 'alpha-core' ),
							'icon'  => 'eicon-v-align-middle',
						),
						'bottom' => array(
							'title' => esc_html__( 'Bottom', 'alpha-core' ),
							'icon'  => 'eicon-v-align-bottom',
						),
					),
					'toggle'      => false,
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'timeline_content',
			array(
				'label' => esc_html__( 'General', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'content_shadow',
				array(
					'label'       => esc_html__( 'Enable Shadow', 'alpha-core' ),
					'description' => esc_html__( 'Add box-shadow to timeline card.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'default'     => 'yes',
				)
			);

			$this->add_control(
				'content_color',
				array(
					'label'       => esc_html__( 'Background', 'alpha-core' ),
					'description' => esc_html__( 'Controls the background color of timeline card', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .timeline-content-inner' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'content_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .timeline-content-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'content_padding',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .timeline-content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'content_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'rem' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .timeline-content-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'timeline_line_color',
				array(
					'label'       => esc_html__( 'Line Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the color of timeline.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .timeline-line' => 'background-color: {{VALUE}};',
					),
					'condition'   => array(
						'timeline_custom_line!' => 'yes',
					),
					'separator'   => 'before',
				)
			);

			$this->add_control(
				'timeline_line_width',
				array(
					'label'       => esc_html__( 'Thickness', 'alpha-core' ),
					'description' => esc_html__( 'Determines the height of line.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .timeline-line' => 'height: {{SIZE}}px;',
					),
					'condition'   => array(
						'timeline_custom_line!' => 'yes',
					),
				)
			);

			$this->add_control(
				'custom_line_rotate',
				array(
					'label'       => esc_html__( 'Custom Line Rotate', 'alpha-core' ),
					'description' => esc_html__( 'Rotate the custom line image.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 360,
						),
					),
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .timeline-point-wrap > img' => 'transform: translate(-50%) rotate({{SIZE}}deg);',
					),
					'condition'   => array(
						'timeline_custom_line' => 'yes',
					),
					'separator'   => 'before',
				)
			);

			$this->add_control(
				'custom_line_spacing',
				array(
					'label'       => esc_html__( 'Custom Line Spacing', 'alpha-core' ),
					'description' => esc_html__( 'Controls the spacing of custom line image.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => -100,
							'max'  => 100,
						),
						'%'  => array(
							'step' => 1,
							'min'  => -100,
							'max'  => 100,
						),
					),
					'size_units'  => array( 'px', '%' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .timeline-point-wrap > img' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'condition'   => array(
						'timeline_custom_line' => 'yes',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'timeline_media',
			array(
				'label' => esc_html__( 'Media', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
			$this->add_control(
				'image_size',
				array(
					'label'       => esc_html__( 'Image Size', 'alpha-core' ),
					'description' => esc_html__( 'Controls the size of timeline item media.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 300,
						),
						'%'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
					),
					'size_units'  => array( 'px', '%' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .timeline-media img' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'image_border_width',
				array(
					'label'      => esc_html__( 'Border Width', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'rem' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .timeline-media img' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
					),
				)
			);

			$this->add_control(
				'image_border_color',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .timeline-media img' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'image_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'rem' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .timeline-media img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'timeline_icon_size',
				array(
					'label'       => esc_html__( 'Icon/Svg Size', 'alpha-core' ),
					'description' => esc_html__( 'If you select media type as icon/svg, you can control size using this option.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
						'%',
						'rem',
						'em',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .timeline-media i' => 'font-size: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .timeline-media svg' => 'width: {{SIZE}}{{UNIT}};',
					),
					'separator'   => 'before',
				)
			);

			$this->add_control(
				'timeline_icon_color',
				array(
					'label'       => esc_html__( 'Icon/Svg Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the color of icon or svg.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .timeline-media i' => 'color: {{VALUE}};',
						'.elementor-element-{{ID}} .timeline-media svg' => 'fill: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'timeline_title',
			array(
				'label' => esc_html__( 'Title', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'title_typography',
					'selector' => '.elementor-element-{{ID}} .timeline-title',
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .timeline-title' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'title_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .timeline-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'timeline_meta',
			array(
				'label' => esc_html__( 'Meta', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'meta_typography',
					'selector' => '.elementor-element-{{ID}} .timeline-meta',
				)
			);

			$this->add_control(
				'meta_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .timeline-meta' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'meta_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .timeline-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'timeline_desc',
			array(
				'label' => esc_html__( 'Description', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'desc_typography',
					'selector' => '.elementor-element-{{ID}} .timeline-desc',
				)
			);

			$this->add_control(
				'desc_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .timeline-desc' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'desc_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .timeline-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'timeline_point',
			array(
				'label' => esc_html__( 'BreakPoint', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
			$this->start_controls_tabs( 'tabs_point_text_style' );
				$this->start_controls_tab(
					'tab_point_text',
					array(
						'label' => esc_html__( 'Text/Icon', 'alpha-core' ),
					)
				);

					$this->add_group_control(
						Group_Control_Typography::get_type(),
						array(
							'name'     => 'point_text',
							'selector' => '.elementor-element-{{ID}} .timeline-point',
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_point_icon',
					array(
						'label' => esc_html__( 'Svg', 'alpha-core' ),
					)
				);

					$this->add_control(
						'icon_size',
						array(
							'label'      => esc_html__( 'Svg Width', 'alpha-core' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => array(
								'px',
								'%',
								'em',
							),
							'selectors'  => array(
								'.elementor-element-{{ID}} .timeline-point svg' => 'width: {{SIZE}}{{UNIT}};',
							),
						)
					);

				$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->start_controls_tabs(
				'tabs_point_style',
				array( 'separator' => 'before' )
			);
				$this->start_controls_tab(
					'tab_point_style',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);
					$this->add_control(
						'point_size',
						array(
							'label'       => esc_html__( 'Size', 'alpha-core' ),
							'description' => esc_html__( 'Controls the background size of breakpoint.', 'alpha-core' ),
							'type'        => Controls_Manager::SLIDER,
							'size_units'  => array(
								'px',
								'%',
								'rem',
							),
							'range'       => array(
								'px'  => array(
									'step' => 1,
									'min'  => 0,
									'max'  => 200,
								),
								'%'   => array(
									'step' => 1,
									'min'  => 0,
									'max'  => 100,
								),
								'rem' => array(
									'step' => 1,
									'min'  => 0,
									'max'  => 20,
								),
							),
							'selectors'   => array(
								'.elementor-element-{{ID}} .timeline' => '--alpha-point-size: {{SIZE}}{{UNIT}};',
							),
						)
					);

					$this->add_control(
						'point_color',
						array(
							'label'     => esc_html__( 'Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .timeline-point' => 'color: {{VALUE}};fill: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'point_bg_color',
						array(
							'label'     => esc_html__( 'Background Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .timeline-point' => 'background-color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'point_border_radius',
						array(
							'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => array( 'px', '%', 'rem' ),
							'selectors'  => array(
								'.elementor-element-{{ID}} .timeline-point' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						array(
							'name'     => 'point_box_shadow',
							'selector' => '.elementor-element-{{ID}} .timeline-point',
						)
					);
				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_point_active',
					array(
						'label' => esc_html__( 'Active', 'alpha-core' ),
					)
				);

					$this->add_control(
						'point_color_active',
						array(
							'label'     => esc_html__( 'Active Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .active .timeline-point' => 'color: {{VALUE}};fill: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'point_bg_color_active',
						array(
							'label'     => esc_html__( 'Active Background Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .active .timeline-point' => 'background-color: {{VALUE}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						array(
							'name'     => 'point_box_shadow_active',
							'label'    => esc_html__( 'Active Box Shadow', 'alpha-core' ),
							'selector' => '.elementor-element-{{ID}} .active .timeline-point',
						)
					);
				$this->end_controls_tab();
			$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/timeline-horizontal/render-timeline-horizontal-elementor.php' );
	}
}
