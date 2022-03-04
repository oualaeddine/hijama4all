<?php
/**
 * Awesome Contact form7 for Elementor
 *
 * @since 1.0.0
 */
namespace AEP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Bootstrap Elementor Pack alert widget.
 *
 * Elementor widget that displays a collapsible display of content in an toggle
 * style, allowing the user to open multiple items.
 *
 * @since 1.0.0
 */
class AEP_Widget_Cf7 extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve alert widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'aep-contact-form_7';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve alert widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'AEP Contact Form 7', 'aep' );
	}
	public function get_categories() {
		return [ 'basic' ];
	}
	/**
	 * Get widget icon.
	 *
	 * Retrieve alert widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'contact', 'form', 'contactForm7' ];
	}

	/**
	 * Register alert widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	 
	protected function _register_controls() {
	   if (!function_exists('wpcf7')) {
		$this->start_controls_section(
			'eael_global_warning',
			[
				'label' => __('Warning!', 'aep'),
			]
		);

		$this->add_control(
			'aep_warning_text',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __('<strong>Awesome Contact Form 7 for Elementor</strong> requires <strong>Contact Form 7</strong> to be installed/activated on your site. Please install and activate <strong>Contact Form 7</strong> first.', 'aep'),
				'content_classes' => 'aep-alert',
			]
		);

		$this->end_controls_section();
	} else {	
		$this->start_controls_section(
			'section_cf7',
			[
				'label' => __( 'Contact Form7', 'aep' ),
			]
		);

		$this->add_control(
			'aep_cf7',
			[
				'label' => esc_html__( 'Select Contact Form', 'aep' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => aep_get_contact_form7(),
			]
		);
		$this->add_control(
			'aep_cf7_title',
			[
				'label' => __( 'Title', 'aep' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '', 'aep' ),
				'placeholder' => __( 'Type your title here', 'aep' ),
			]
		);
		  $this->add_control(
			'aep_cf7_sub_title',
			[
				'label' => __( 'Sub Title', 'aep' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( '', 'aep' ),
				'placeholder' => __( 'Type your sub title here', 'aep' ),
			]
		);
		$this->end_controls_section();
	}
		/*Title Style*/
		$this->start_controls_section(
		'cf7_title_style',
		[
			'label' => __( 'Title Style', 'aep' ),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);
	$this->add_responsive_control(
		'cf7_title_align',
		[
			'label' => __( 'Alignment', 'aep' ),
			'type' => \Elementor\Controls_Manager::CHOOSE,
			'options' => [
				'left'    => [
					'title' => __( 'Left', 'aep' ),
					'icon' => 'fa fa-align-left',
				],
				'center' => [
					'title' => __( 'Center', 'aep' ),
					'icon' => 'fa fa-align-center',
				],
				'right' => [
					'title' => __( 'Right', 'aep' ),
					'icon' => 'fa fa-align-right',
				]
			],
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .aep-cf7 .cf7-title' => 'text-align: {{VALUE}};',
				'{{WRAPPER}} .aep-cf7 .cf7-sub-title' => 'text-align: {{VALUE}};',
			],
		]
	);
	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'label' => 'Title Typography',
			'name' => 'title_typography',
			'selector' => '{{WRAPPER}} .aep-cf7 .cf7-title',
		]
	);
	$this->add_control(
		'cf7_title_color',
		[
			'label' => __( 'Title Color', 'aep' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#333',
			'selectors' => [
				'{{WRAPPER}} .aep-cf7 .cf7-title' => 'color: {{VALUE}};',
			],
		]
	);
	
	$this->add_control(
		'cf7_title_margin',
		[
			'label' => __( 'Margin', 'aep' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{WRAPPER}} .aep-cf7 .cf7-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		'separator'	=> 'after'
			
		]
	);
		$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'label' => 'Sub Title Typography',
			'name' => 'sub_title_typography',
			'selector' => '{{WRAPPER}} .aep-cf7 .cf7-sub-title',
		]
	);
	$this->add_control(
		'cf7_sub_title_color',
		[
			'label' => __( 'Sub Title Color', 'aep' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#333',
			'selectors' => [
				'{{WRAPPER}} .aep-cf7 .cf7-sub-title' => 'color: {{VALUE}};',
			],
		]
	);
	$this->add_control(
		'cf7_sub_title_margin',
		[
			'label' => __( 'Margin', 'aep' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors' => [
				'{{WRAPPER}} .aep-cf7 .cf7-sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],	
		]
	);

	
$this->end_controls_section();
		$this->start_controls_section(
			'cf7_label_style',
			[
				'label' => __( 'Label Style', 'aep' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'cf7_label_align',
			[
				'label' => __( 'Alignment', 'aep' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'aep' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'aep' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'aep' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7-form label' => 'text-align: {{cf7_label_align}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => 'Label Typography',
				'name' => 'tile_typography',
				'selector' => '{{WRAPPER}} .wpcf7-form label,.wpcf7-form input::placeholder, 
				.wpcf7-form textarea::placeholder ',
			]
		);
		$this->add_control(
			'cf_7_label_color',
			[
				'label' => __( 'Label Color', 'aep' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpcf7-form label' => 'color: {{VALUE}};',
				],
			]
		);	

		$this->add_control(
			'cf_7_label_placeholder_color',
			[
				'label' => __( 'Placeholder Color', 'aep' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				'selectors' => [
					'{{WRAPPER}} .wpcf7-form ::placeholder' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'cf_7_label_space',
			[
				'label' => __( 'Label Spacing', 'aep' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpcf7 input[type="text"], 
					.wpcf7 input[type="email"], 
					.wpcf7 textarea, 
					.wpcf7 input[type="date"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'cf_7_container_padding',
			[
				'label' => __( 'Form Padding', 'aep' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpcf7-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'cf7_form_style',
			[
				'label' => __( 'Form Style', 'aep' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'wpcf7_form',
			[
				'label' => __( 'Alignment', 'aep' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'aep' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'aep' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'aep' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .aep-cf7' => 'float: {{wpcf7_form}};',
				],
			]
		);
		$this->add_control(
			'aep_cf7_form_style',
			[
				'label' => esc_html__( 'Form Style', 'aep' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'style-normal' => __( 'Normal', 'aep' ),
					'style-1'  => __( 'Style 1', 'aep' ),
					'style-2'  => __( 'Style 2', 'aep' ),
				],
			]
		);
		$this->add_control(
			'cf_7_border_color',
			[
				'label' => __( 'Input Border Color', 'aep' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .aep-cf7 .wpcf7 input[type="text"], 
					{{WRAPPER}} .aep-cf7 .wpcf7 input[type="email"], 
					{{WRAPPER}} .wpcf7 input[type="date"],
					{{WRAPPER}} .aep-cf7 .wpcf7 textarea' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'cf_7_input_text_color',
			[
				'label' => __( 'Input Text Color', 'aep' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .aep-cf7 .wpcf7 input[type="text"], 
					{{WRAPPER}} .aep-cf7 .wpcf7 input[type="email"],
					{{WRAPPER}} .wpcf7 input[type="date"],
					{{WRAPPER}} .aep-cf7 .wpcf7 textarea' => 'color:{{VALUE}};',
				],
			]
		);
		$this->add_control(
			'cf_7_border_focus_color',
			[
				'label' => __( 'Input Focus Border', 'aep' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .aep-cf7 .wpcf7 input[type="text"]:focus, 
					{{WRAPPER}} .aep-cf7 .wpcf7 input[type="email"]:focus,
					{{WRAPPER}} .aep-cf7 .wpcf7 input[type="date"]:focus,
					{{WRAPPER}} .aep-cf7 .wpcf7 textarea:focus' => 'border-color:{{VALUE}};',
				],
			]
		);
		
		
		$this->add_control(
			'cf7_form_bg',
			[
				'label' => __( 'Input Background', 'aep' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .aep-cf7 .wpcf7 input[type="text"], 
					.aep-cf7 .wpcf7 input[type="email"], 
					.aep-cf7 .wpcf7 input[type="date"],
					.aep-cf7 .wpcf7 textarea' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cf7_form_border',
				'selector' => '{{WRAPPER}} .aep-cf7 .wpcf7 input[type="text"], 
				.aep-cf7 .wpcf7 input[type="email"],
				.aep-cf7 .wpcf7 input[type="date"],
				.aep-cf7 .wpcf7 textarea',
				'condition' => [
					'aep_cf7_form_style' => 'style-normal',
				],
			]
		);
		$this->add_control(
			'cf7_form_radius',
			[
				'label' => __( 'Border Radius', 'aep' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .aep-cf7 .wpcf7 input[type="text"], 
					.aep-cf7 .wpcf7 input[type="email"], 
					.aep-cf7 .wpcf7 input[type="date"],
					.aep-cf7 .wpcf7 textarea, 
					.aep-cf7 .wpcf7 input[type="submit"], 
					.aep-cf7 .wpcf7 textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'=>'after'
			]
		);
		$this->add_responsive_control(
  			'cf7_input_width',
  			[
  				'label' => __( 'Width', 'aep' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%','px', 'em' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 1200,
					],
					'em' => [
						'min' => 1,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .aep-cf7 .wpcf7 input[type="text"], 
					.aep-cf7 .wpcf7 input[type="email"],
					.aep-cf7 .wpcf7 input[type="date"],
					.aep-cf7 .wpcf7 textarea, 
					.aep-cf7 .wpcf7-form' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);  
		/*Textarea Height*/
        $this->add_responsive_control(
  			'cf7_input_height',
  			[
  				'label' => __( 'Input Height', 'aep' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 100,
					],
					'em' => [
						'min' => 1,
						'max' => 40,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .aep-cf7 .wpcf7 input[type="text"], 
					.aep-cf7 .wpcf7 input[type="date"],
					.aep-cf7 .wpcf7 input[type="email"]' => 'height: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
		
		/*Textarea Height*/
        $this->add_responsive_control(
  			'cf7_textarea_height',
  			[
  				'label' => __( 'Textarea Height', 'aep' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 300,
					],
					'em' => [
						'min' => 1,
						'max' => 40,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 125,
				],
				'selectors' => [
					'{{WRAPPER}} .aep-cf7 .wpcf7 textarea' => 'height: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'cf7_button_style',
			[
				'label' => __( 'Button Style', 'aep' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_btn_normal',
			[
				'label' => __( 'Normal', 'aep' ),
			]
		);
		$this->add_control(
			'cf7_button_bg',
			[
				'label' => __( 'Button Background', 'aep' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333',
				'selectors' => [
					'{{WRAPPER}} .aep-cf7 .wpcf7 input[type="submit"]' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'cf7_button_radius',
			[
				'label' => __( 'Border Radius', 'aep' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .aep-cf7 .wpcf7 input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		$this->add_control(
			'cf7_button_margin',
			[
				'label' => __( 'Margin', 'aep' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .aep-cf7 .wpcf7 input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		$this->add_control(
			'cf7_button_border',
			[
				'label' => __( 'Border Color', 'aep' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				'selectors' => [
					'{{WRAPPER}} .aep-cf7 .wpcf7 input[type="submit"]' => 'border:1px solid {{VALUE}};',
				],
				'separator'=>'after'
			]
		);
		$this->add_control(
			'cf7_button_text_color',
			[
				'label' => __( 'Button Text Color', 'aep' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .aep-cf7 .wpcf7 input[type="submit"]' => 'color:{{VALUE}};',
				],
			]
		);
		
		 $this->add_responsive_control(
  			'cf7_button_width',
  			[
  				'label' => __( 'Width', 'aep' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%','px', 'em',  ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 1200,
					],
					'em' => [
						'min' => 1,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} input.wpcf7-submit' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);  
        
        /*Button Height*/
        $this->add_responsive_control(
  			'cf7_button_height',
  			[
  				'label' => __( 'Height', 'aep' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 500,
					],
					'em' => [
						'min' => 1,
						'max' => 40,
					],
				],
				'selectors' => [
					'{{WRAPPER}} input.wpcf7-submit' => 'height: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
        $this->end_controls_tab();
		$this->start_controls_tab(
			'tab_btn_hover',
			[
				'label' => __( 'Hover', 'aep' ),
			]
		);
		$this->add_control(
			'cf7_button_hover_bg',
			[
				'label' => __( 'Button Background', 'aep' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333',
				'selectors' => [
					'{{WRAPPER}} .aep-cf7 .wpcf7 input[type="submit"]:hover' => 'background: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		

	}

	/**
	 * Render alert widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		 if (!function_exists('wpcf7')) {
            return;
        }
	$settings = $this->get_settings_for_display();
		if(!empty($settings['aep_cf7'])){
		   echo'<div class="elementor-shortcode aep-cf7 '.$settings['aep_cf7_form_style'].' aep-cf7-'.$settings['aep_cf7'].'">';
				echo '<h3 class="cf7-title">'. $settings['aep_cf7_title'].'</h3>';
			    echo '<div class="cf7-sub-title">'. $settings['aep_cf7_sub_title'].'</div>';
				echo do_shortcode('[contact-form-7 id="'.$settings['aep_cf7'].'"]');    
		   echo '</div>';  
		}
}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new AEP_Widget_Cf7() );
