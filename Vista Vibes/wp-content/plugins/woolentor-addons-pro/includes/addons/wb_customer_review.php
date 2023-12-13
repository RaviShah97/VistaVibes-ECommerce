<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Wb_Customer_Review_Widget extends Widget_Base {

    public function get_name() {
        return 'wl-customer-veview';
    }

    public function get_title() {
        return __( 'WL: Customer Review', 'woolentor-pro' );
    }

    public function get_icon() {
        return 'eicon-comments';
    }

    public function get_categories() {
        return array( 'woolentor-addons' );
    }

    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    public function get_style_depends(){
        return [
            'slick',
            'woolentor-widgets',
        ];
    }

    public function get_script_depends() {
        return [
            'slick',
            'woolentor-widgets-scripts',
        ];
    }

    public function get_keywords(){
        return ['review','customer','product review','customer review'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'review_content',
            [
                'label' => __( 'Review', 'woolentor-pro' ),
            ]
        );
            
            $this->add_control(
                'review_layout',
                [
                    'label' => __( 'Style', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Style One', 'woolentor-pro' ),
                        '2'   => __( 'Style Two', 'woolentor-pro' ),
                        '3'   => __( 'Style Three', 'woolentor-pro' ),
                        '4'   => __( 'Style Four', 'woolentor-pro' ),
                    ],
                ]
            );

            $this->add_control(
                'review_type',
                [
                    'label' => __( 'Review Type', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'custom',
                    'options' => [
                        'custom'      => __( 'Custom', 'woolentor-pro' ),
                        'allproduct'  => __( 'All Products', 'woolentor-pro' ),
                        'productwise' => __( 'Single Product', 'woolentor-pro' ),
                        'dynamic'     => __( 'Dynamic', 'woolentor-pro' ),
                    ],
                ]
            );

            $this->add_control(
                'important_note',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<div style="line-height:18px;">'.esc_html__('If you select "Dynamic", it will work on the single product page only.','woolentor-pro').'</div>',
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                    'condition' => [
                        'review_type' => 'dynamic',
                    ]
                ]
            );

            $this->add_control(
                'product_id',
                [
                    'label' => __( 'Select Product', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'options' => woolentor_post_name( 'product' ),
                    'condition' => [
                        'review_type' => 'productwise',
                    ]
                ]
            );

            $this->add_control(
                'limit',
                [
                    'label' => __( 'Limit', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'step' => 1,
                    'condition' =>[
                        'review_type' => 'allproduct',
                    ]
                ]
            );

            $this->add_control(
                'offset',
                [
                    'label' => __( 'Offset', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'step' => 1,
                    'condition' =>[
                        'review_type' => 'allproduct',
                    ]
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'client_name',
                [
                    'label'   => __( 'Name', 'woolentor-pro' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __('Carolina Monntoya','woolentor-pro'),
                ]
            );

            $repeater->add_control(
                'client_designation',
                [
                    'label'   => __( 'Designation', 'woolentor-pro' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __('Managing Director','woolentor-pro'),
                ]
            );

            $repeater->add_control(
                'client_rating',
                [
                    'label' => __( 'Client Rating', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 5,
                    'step' => 1,
                ]
            );

            $repeater->add_control(
                'client_image',
                [
                    'label' => __( 'Image', 'woolentor-pro' ),
                    'type' => Controls_Manager::MEDIA,
                ]
            );

            $repeater->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'client_imagesize',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $repeater->add_control(
                'client_say',
                [
                    'label'   => __( 'Client Say', 'woolentor-pro' ),
                    'type'    => Controls_Manager::TEXTAREA,
                    'default' => __('Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','woolentor-pro'),
                ]
            );

            $this->add_control(
                'review_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'condition'=>[
                        'review_type' => 'custom',
                    ],
                    'fields'  => $repeater->get_controls(),
                    'default' => [

                        [
                            'client_name' => __('Carolina Monntoya','woolentor-pro'),
                            'client_designation' => __( 'Managing Director','woolentor-pro' ),
                            'client_rating'=>'5',
                            'client_say' => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'woolentor-pro' ),
                        ],

                        [
                            'client_name' => __('Peter Rose','woolentor-pro'),
                            'client_designation' => __( 'Manager','woolentor-pro' ),
                            'client_rating'=>'5',
                            'client_say' => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'woolentor-pro' ),
                        ],

                        [
                            'client_name' => __('Gerald Gilbert','woolentor-pro'),
                            'client_designation' => __( 'Developer','woolentor-pro' ),
                            'client_rating'=>'5',
                            'client_say' => __( 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'woolentor-pro' ),
                        ],
                    ],
                    'title_field' => '{{{ client_name }}}',
                ]
            );

            $this->add_control(
                'slider',
                [
                    'label' => esc_html__( 'Slider', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off' => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator' => 'before'
                ]
            );

        $this->end_controls_section();

        // Options
        $this->start_controls_section(
            'review_option',
            [
                'label' => __( 'Option', 'woolentor-pro' ),
                'condition'=>[
                    'slider!' => 'yes',
                ],
            ]
        );
            
            $this->add_responsive_control(
                'column',
                [
                    'label' => esc_html__( 'Columns', 'woolentor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '3',
                    'options' => [
                        '1' => esc_html__( 'One', 'woolentor-pro' ),
                        '2' => esc_html__( 'Two', 'woolentor-pro' ),
                        '3' => esc_html__( 'Three', 'woolentor-pro' ),
                        '4' => esc_html__( 'Four', 'woolentor-pro' ),
                        '5' => esc_html__( 'Five', 'woolentor-pro' ),
                        '6' => esc_html__( 'Six', 'woolentor-pro' ),
                        '7' => esc_html__( 'Seven', 'woolentor-pro' ),
                        '8' => esc_html__( 'Eight', 'woolentor-pro' ),
                        '9' => esc_html__( 'Nine', 'woolentor-pro' ),
                        '10'=> esc_html__( 'Ten', 'woolentor-pro' ),
                    ],
                    'label_block' => true,
                    'prefix_class' => 'wl-columns%s-',
                ]
            );

            $this->add_control(
                'no_gutters',
                [
                    'label' => esc_html__( 'No Gutters', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'woolentor-pro' ),
                    'label_off' => esc_html__( 'No', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_responsive_control(
                'item_space',
                [
                    'label' => esc_html__( 'Space', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 15,
                    ],
                    'condition'=>[
                        'no_gutters!'=>'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-row > [class*="col-"]' => 'padding: 0  {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'item_bottom_space',
                [
                    'label' => esc_html__( 'Bottom Space', 'woolentor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 30,
                    ],
                    'condition'=>[
                        'no_gutters!'=>'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-row > [class*="col-"]' => 'margin-bottom:{{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'show_image',
                [
                    'label' => __( 'Show Thumbnail', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'woolentor-pro' ),
                    'label_off' => __( 'Hide', 'woolentor-pro' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition'=>[
                        'review_type!' => 'custom',
                    ],
                ]
            );

        $this->end_controls_section();

        // Slider setting
        $this->start_controls_section(
            'customer_review_slider',
            [
                'label' => esc_html__( 'Slider Option', 'woolentor-pro' ),
                'condition' => [
                    'slider' => 'yes',
                ]
            ]
        );

            $this->add_control(
                'slitems',
                [
                    'label' => esc_html__( 'Slider Items', 'woolentor-pro' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 3
                ]
            );

            $this->add_control(
                'slarrows',
                [
                    'label' => esc_html__( 'Slider Arrow', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'sldots',
                [
                    'label' => esc_html__( 'Slider dots', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no'
                ]
            );

            $this->add_control(
                'slpause_on_hover',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __('No', 'woolentor-pro'),
                    'label_on' => __('Yes', 'woolentor-pro'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'label' => __('Pause on Hover?', 'woolentor-pro'),
                ]
            );

            $this->add_control(
                'slautolay',
                [
                    'label' => esc_html__( 'Slider autoplay', 'woolentor-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator' => 'before',
                    'default' => 'no'
                ]
            );

            $this->add_control(
                'slautoplay_speed',
                [
                    'label' => __('Autoplay speed', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 3000,
                    'condition' => [
                        'slautolay' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slanimation_speed',
                [
                    'label' => __('Autoplay animation speed', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 300,
                    'condition' => [
                        'slautolay' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slscroll_columns',
                [
                    'label' => __('Slider item to scroll', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 3,
                ]
            );

            $this->add_control(
                'heading_tablet',
                [
                    'label' => __( 'Tablet', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'sltablet_display_columns',
                [
                    'label' => __('Slider Items', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 8,
                    'step' => 1,
                    'default' => 2,
                ]
            );

            $this->add_control(
                'sltablet_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 8,
                    'step' => 1,
                    'default' => 2,
                ]
            );

            $this->add_control(
                'sltablet_width',
                [
                    'label' => __('Tablet Resolution', 'woolentor-pro'),
                    'description' => __('The resolution to the tablet.', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 750,
                ]
            );

            $this->add_control(
                'heading_mobile',
                [
                    'label' => __( 'Mobile Phone', 'woolentor-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'slmobile_display_columns',
                [
                    'label' => __('Slider Items', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 4,
                    'step' => 1,
                    'default' => 1,
                ]
            );

            $this->add_control(
                'slmobile_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 4,
                    'step' => 1,
                    'default' => 1,
                ]
            );

            $this->add_control(
                'slmobile_width',
                [
                    'label' => __('Mobile Resolution', 'woolentor-pro'),
                    'description' => __('The resolution to mobile.', 'woolentor-pro'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 480,
                ]
            );

        $this->end_controls_section(); // Slider Option end

        // Style style start
        $this->start_controls_section(
            'testimonial_area_style',
            [
                'label'     => __( 'Area', 'woolentor-pro' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'testimonial_content_align',
                [
                    'label' => __( 'Alignment', 'woolentor-pro' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'woolentor-pro' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-customer-testimonal' => 'text-align: {{VALUE}};',
                    ],
                    'prefix_class' => 'wl-customer-align%s-',
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'testimonial_area_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-customer-testimonal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'testimonial_area_background',
                    'label' => __( 'Background', 'woolentor-pro' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .wl-customer-testimonal',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'testimonial_area_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .wl-customer-testimonal',
                ]
            );

            $this->add_responsive_control(
                'testimonial_area_border_radius',
                [
                    'label' => __( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-customer-testimonal' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section();

        // Style image style start
        $this->start_controls_section(
            'testimonial_image_style',
            [
                'label'     => __( 'Image', 'woolentor-pro' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'testimonial_image_border',
                    'label' => __( 'Border', 'woolentor-pro' ),
                    'selector' => '{{WRAPPER}} .wl-customer-testimonal img',
                ]
            );

            $this->add_responsive_control(
                'testimonial_image_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .wl-customer-testimonal img' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

        $this->end_controls_section(); // Style Testimonial image style end

        // Style Testimonial name style start
        $this->start_controls_section(
            'testimonial_name_style',
            [
                'label'     => __( 'Name', 'woolentor-pro' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            
            $this->add_control(
                'testimonial_name_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wl-customer-testimonal .clint-info h4' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .wlb-review-style-2 .wl-customer-testimonal .clint-info h4:before' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'testimonial_name_typography',
                    'selector' => '{{WRAPPER}} .wl-customer-testimonal .clint-info h4',
                ]
            );

            $this->add_responsive_control(
                'testimonial_name_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-customer-testimonal .clint-info h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'testimonial_name_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-customer-testimonal .clint-info h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Style Testimonial name style end

        // Style Testimonial designation style start
        $this->start_controls_section(
            'testimonial_designation_style',
            [
                'label'     => __( 'Designation', 'woolentor-pro' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
            $this->add_control(
                'testimonial_designation_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wl-customer-testimonal .clint-info span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'testimonial_designation_typography',
                    'selector' => '{{WRAPPER}} .wl-customer-testimonal .clint-info span',
                ]
            );

            $this->add_responsive_control(
                'testimonial_designation_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-customer-testimonal .clint-info span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'testimonial_designation_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-customer-testimonal .clint-info span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Style Testimonial designation style end

        // Style Testimonial designation style start
        $this->start_controls_section(
            'testimonial_clientsay_style',
            [
                'label'     => __( 'Client say', 'woolentor-pro' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'testimonial_clientsay_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wl-customer-testimonal p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'testimonial_clientsay_typography',
                    'selector' => '{{WRAPPER}} .wl-customer-testimonal p',
                ]
            );

            $this->add_responsive_control(
                'testimonial_clientsay_margin',
                [
                    'label' => __( 'Margin', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-customer-testimonal p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'testimonial_clientsay_padding',
                [
                    'label' => __( 'Padding', 'woolentor-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .wl-customer-testimonal p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

        $this->end_controls_section(); // Style Testimonial designation style end

        // Style Testimonial designation style start
        $this->start_controls_section(
            'testimonial_clientrating_style',
            [
                'label'     => __( 'Rating', 'woolentor-pro' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'testimonial_clientrating_color',
                [
                    'label' => __( 'Color', 'woolentor-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wl-customer-testimonal .clint-info .rating' => 'color: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section(); // Style Testimonial designation style end

        // Slider Button style
        $this->start_controls_section(
            'slider_controller_style',
            [
                'label' => esc_html__( 'Slider Controller Style', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'slider' => 'yes',
                ]
            ]
        );

            $this->start_controls_tabs('product_sliderbtn_style_tabs');

                // Slider Button style Normal
                $this->start_controls_tab(
                    'product_sliderbtn_style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'woolentor-pro' ),
                    ]
                );

                    $this->add_control(
                        'button_style_heading',
                        [
                            'label' => esc_html__( 'Navigation Arrow', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_responsive_control(
                        'nvigation_position',
                        [
                            'label' => esc_html__( 'Position', 'woolentor-pro' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 5,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => '%',
                                'size' => 50,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'top: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_bg_color',
                        [
                            'label' => esc_html__( 'Background Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_border',
                            'label' => esc_html__( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .product-slider .slick-arrow',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'button_padding',
                        [
                            'label' => esc_html__( 'Padding', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_style_dots_heading',
                        [
                            'label' => esc_html__( 'Navigation Dots', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                        $this->add_responsive_control(
                            'dots_position',
                            [
                                'label' => esc_html__( 'Position', 'woolentor-pro' ),
                                'type' => Controls_Manager::SLIDER,
                                'size_units' => [ 'px', '%' ],
                                'range' => [
                                    'px' => [
                                        'min' => 0,
                                        'max' => 1000,
                                        'step' => 5,
                                    ],
                                    '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                ],
                                'default' => [
                                    'unit' => '%',
                                    'size' => 50,
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots' => 'left: {{SIZE}}{{UNIT}};',
                                ],
                            ]
                        );

                        $this->add_control(
                            'dots_bg_color',
                            [
                                'label' => esc_html__( 'Background Color', 'woolentor-pro' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button' => 'background-color: {{VALUE}} !important;',
                                ],
                            ]
                        );

                        $this->add_group_control(
                            Group_Control_Border::get_type(),
                            [
                                'name' => 'dots_border',
                                'label' => esc_html__( 'Border', 'woolentor-pro' ),
                                'selector' => '{{WRAPPER}} .product-slider .slick-dots li button',
                            ]
                        );

                        $this->add_responsive_control(
                            'dots_border_radius',
                            [
                                'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                ],
                            ]
                        );

                $this->end_controls_tab();// Normal button style end

                // Button style Hover
                $this->start_controls_tab(
                    'product_sliderbtn_style_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'woolentor-pro' ),
                    ]
                );


                    $this->add_control(
                        'button_style_arrow_heading',
                        [
                            'label' => esc_html__( 'Navigation', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_control(
                        'button_hover_color',
                        [
                            'label' => esc_html__( 'Color', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_hover_bg_color',
                        [
                            'label' => esc_html__( 'Background', 'woolentor-pro' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_hover_border',
                            'label' => esc_html__( 'Border', 'woolentor-pro' ),
                            'selector' => '{{WRAPPER}} .product-slider .slick-arrow:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );


                    $this->add_control(
                        'button_style_dotshov_heading',
                        [
                            'label' => esc_html__( 'Navigation Dots', 'woolentor-pro' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                        $this->add_control(
                            'dots_hover_bg_color',
                            [
                                'label' => esc_html__( 'Background Color', 'woolentor-pro' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button:hover' => 'background-color: {{VALUE}} !important;',
                                    '{{WRAPPER}} .product-slider .slick-dots li.slick-active button' => 'background-color: {{VALUE}} !important;',
                                ],
                            ]
                        );

                        $this->add_group_control(
                            Group_Control_Border::get_type(),
                            [
                                'name' => 'dots_border_hover',
                                'label' => esc_html__( 'Border', 'woolentor-pro' ),
                                'selector' => '{{WRAPPER}} .product-slider .slick-dots li button:hover',
                            ]
                        );

                        $this->add_responsive_control(
                            'dots_border_radius_hover',
                            [
                                'label' => esc_html__( 'Border Radius', 'woolentor-pro' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                ],
                            ]
                        );

                $this->end_controls_tab();// Hover button style end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Tab option end

    }


    protected function render( $instance = [] ) {

        $settings  = $this->get_settings_for_display();
        $column    = $this->get_settings_for_display('column');

        $this->add_render_attribute( 'review_area_attr', 'class', 'wl-customer-review wlb-review-style-'.$settings['review_layout'] );

        $collumval = 'wl-col-6';
        if( $column !='' ){
            $collumval = 'wl-col-'.$column;
        }

        // Slider Options
        $slider_main_div_style = '';
        if( $settings['slider'] === 'yes' ){
            $is_rtl = is_rtl();
            $direction = $is_rtl ? 'rtl' : 'ltr';
            $slider_settings = [
                'arrows' => ('yes' === $settings['slarrows']),
                'dots' => ('yes' === $settings['sldots']),
                'autoplay' => ('yes' === $settings['slautolay']),
                'autoplay_speed' => absint($settings['slautoplay_speed']),
                'animation_speed' => absint($settings['slanimation_speed']),
                'pause_on_hover' => ('yes' === $settings['slpause_on_hover']),
                'rtl' => $is_rtl,
            ];

            $slider_responsive_settings = [
                'product_items' => $settings['slitems'],
                'scroll_columns' => $settings['slscroll_columns'],
                'tablet_width' => $settings['sltablet_width'],
                'tablet_display_columns' => $settings['sltablet_display_columns'],
                'tablet_scroll_columns' => $settings['sltablet_scroll_columns'],
                'mobile_width' => $settings['slmobile_width'],
                'mobile_display_columns' => $settings['slmobile_display_columns'],
                'mobile_scroll_columns' => $settings['slmobile_scroll_columns'],

            ];
            $slider_settings = array_merge( $slider_settings, $slider_responsive_settings );
            $slider_main_div_style = "style='display:none'";
        }

        // Generate review
        $review_list = [];
        if( $settings['review_type'] === 'custom' ){
            foreach ( $settings['review_list'] as $review ){
                $review_list[] = array(
                    'image' => Group_Control_Image_Size::get_attachment_image_html( $review, 'client_imagesize', 'client_image' ),
                    'name' => $review['client_name'],
                    'designation' => $review['client_designation'],
                    'ratting' => $review['client_rating'],
                    'message' => $review['client_say'],
                );
            }
        }else{

            if( $settings['review_type'] == 'allproduct' ){
                
                $args = array(
                    'status'=> 'approve',
                    'type'  => 'review',
                );

                if( !empty( $settings['limit'] ) ){
                    $args['number'] = $settings['limit'];
                }

                if( !empty( $settings['offset'] ) ){
                    $args['offset'] = $settings['offset'];
                }

                // The Query
                $comments_query = new \WP_Comment_Query;
                $comments = $comments_query->query( $args );

            }else if( $settings['review_type'] == 'dynamic' ){
                if( woolentor_is_preview_mode() ){
                    $proid = woolentor_get_last_product_id();
                }else{
                    global $product;
                    $product = wc_get_product();
                    if ( empty( $product ) ) { return; }
                    $proid = $product->get_id();
                }
                if( empty( $proid ) ){
                    echo esc_html__( 'Product not found.', 'woolentor-pro' );
                    return;
                }else{
                    $comments = get_comments( 'post_id=' . $proid );
                }
            }else{
                $proid = $settings['product_id'];
                if( empty( $proid ) ){
                    echo esc_html__( 'Please select product.', 'woolentor-pro' );
                    return;
                }else{
                    $comments = get_comments( 'post_id=' . $proid );
                }
            }
            if ( !$comments ){
                return;
            }
            foreach ( $comments as $comment ) {

                $rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
                $user_id   = get_comment( $comment->comment_ID )->user_id;
                $user_info = get_userdata( $user_id );

                $review_list[] = array(
                    'image' => ( $settings['show_image'] == 'yes' ? get_avatar( $comment, '150' ) : '' ),
                    'name' => get_comment_author( $comment ),
                    'designation' => ( !empty( $user_info->roles ) ? implode( ', ', $user_info->roles ): '' ) ,
                    'ratting' => $rating,
                    'message' => $comment->comment_content,
                );

            }

        }

        echo '<div '.$this->get_render_attribute_string( 'review_area_attr' ).'>';
        echo '<div class="wl-row '.( $settings['no_gutters'] === 'yes' ? 'wlno-gutters' : '' ).( $settings['slider'] === 'yes' ? 'product-slider' : '' ).'" data-settings=\''.( $settings['slider'] === 'yes' ? wp_json_encode( $slider_settings ) : '' ). '\' '.$slider_main_div_style.'>';
        ?>
            <?php foreach ( $review_list as $review ): ?>
            <div class="<?php echo esc_attr( esc_attr( $collumval ) ); ?>">

                <?php if( $settings['review_layout'] == 2 || $settings['review_layout'] == 3 ): ?>

                <div class="wl-customer-testimonal">
                    <?php
                        if( $review['image'] ){
                            echo $review['image'];
                        }
                    ?>
                    <div class="content">
                        <?php
                            if( !empty($review['message']) ){
                                echo '<p>'.esc_html__( $review['message'],'woolentor-pro' ).'</p>';
                            }
                        ?>
                        <div class="clint-info">
                            <?php
                                if( !empty( $review['name'] ) ){
                                    echo '<h4>'.esc_html__( $review['name'],'woolentor-pro' ).'</h4>';
                                }
                                if( !empty( $review['designation'] ) ){
                                    echo '<span>'.esc_html__( $review['designation'],'woolentor-pro' ).'</span>';
                                }

                                // Rating
                                if( !empty( $review['ratting'] ) ){
                                    $this->ratting( $review['ratting'] );
                                }
                            ?>
                        </div>
                    </div>
                </div>

                <?php elseif( $settings['review_layout'] == 4 ): ?>
                <div class="wl-customer-testimonal">
                    <div class="content">
                        <?php
                            if( !empty($review['message']) ){
                                echo '<p>'.esc_html__( $review['message'],'woolentor-pro' ).'</p>';
                            }
                        ?>
                        <div class="triangle"></div>
                    </div>
                    <div class="clint-info">
                        <?php
                            if( $review['image'] ){
                                echo $review['image'];
                            }

                            if( !empty( $review['name'] ) ){
                                echo '<h4>'.esc_html__( $review['name'],'woolentor-pro' ).'</h4>';
                            }

                            if( !empty( $review['designation'] ) ){
                                echo '<span>'.esc_html__( $review['designation'],'woolentor-pro' ).'</span>';
                            }

                            // Rating
                            if( !empty( $review['ratting'] ) ){
                                $this->ratting( $review['ratting'] );
                            }

                        ?>
                    </div>
                </div>

                <?php else:?>
                <div class="wl-customer-testimonal">
                    <div class="content">
                        <?php
                            if( $review['image'] ){
                                echo $review['image'];
                            }
                        ?>
                        <div class="clint-info">
                            <?php
                                if( !empty( $review['name'] ) ){
                                    echo '<h4>'.esc_html__( $review['name'],'woolentor-pro' ).'</h4>';
                                }
                                if( !empty( $review['designation'] ) ){
                                    echo '<span>'.esc_html__( $review['designation'],'woolentor-pro' ).'</span>';
                                }
                                
                                // Rating
                                if( !empty( $review['ratting'] ) ){
                                    $this->ratting( $review['ratting'] );
                                }

                            ?>
                        </div>
                    </div>
                    <?php
                        if( !empty($review['message']) ){
                            echo '<p>'.esc_html__( $review['message'],'woolentor-pro' ).'</p>';
                        }
                    ?>
                </div>
            <?php endif; ?>

            </div>
            <?php endforeach;
        echo '</div></div>';
        

    }

    public function ratting( $ratting_num ){
        if( !empty( $ratting_num ) ){
            $rating = $ratting_num;
            $rating_whole = floor( $ratting_num );
            $rating_fraction = $rating - $rating_whole;
            echo '<ul class="rating">';
                for($i = 1; $i <= 5; $i++){
                    if( $i <= $rating_whole ){
                        echo '<li><i class="fa fa-star"></i></li>';
                    } else {
                        if( $rating_fraction != 0 ){
                            echo '<li><i class="fa fa-star-half-o"></i></li>';
                            $rating_fraction = 0;
                        } else {
                            echo '<li><i class="fa fa-star-o"></i></li>';
                        }
                    }
                }
            echo '</ul>';
        }
    }

}