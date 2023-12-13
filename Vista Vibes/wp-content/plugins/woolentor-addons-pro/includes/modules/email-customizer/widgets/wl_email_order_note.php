<?php
namespace Elementor;

use function WooLentor\woolentor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Email order note widget.
 */
class Woolentor_Wl_Email_Order_Note_Widget extends Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'wl-email-order-note';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return esc_html__( 'WL: Order Note to Customer', 'woolentor-pro' );
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-notes';
    }

    /**
     * Get widget categories.
     */
    public function get_categories() {
        return [ 'woolentor-addons-pro' ];
    }

    /**
     * Get help URL.
     */
    public function get_help_url() {
        return 'https://woolentor.com/documentation/';
    }

    /**
     * Get widget keywords.
     */
    public function get_keywords() {
        return [ 'email', 'order', 'note', 'comments' ];
    }

    /**
     * Register order note widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_order_note',
            [
                'label' => esc_html__( 'Order Note', 'woolentor-pro' ),
            ]
        );

        $this->control_for_no_order_found_notice( 1 );

        $this->add_control(
            'order_note_heading',
            [
                'label' => esc_html__( 'Heading', 'woolentor-pro' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => false,
                ],
                'placeholder' => esc_html__( 'Order Note', 'woolentor-pro' ),
                'default' => esc_html__( 'Order Note', 'woolentor-pro' ),
            ]
        );

        $this->add_control(
            'order_note_alt_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__( 'When Order Note is not available', 'woolentor-pro' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'order_note_alt',
            [
                'label' => esc_html__( 'Show', 'woolentor-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none'   => esc_html__( 'Nothing', 'woolentor-pro' ),
                    'custom' => esc_html__( 'Custom Text', 'woolentor-pro' ),
                ],
                'default' => 'none',
            ]
        );

        $this->add_control(
            'order_note_custom',
            [
                'label' => esc_html__( 'Custom Text', 'woolentor-pro' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => false,
                ],
                'condition' => [
                    'order_note_alt' => 'custom',
                ],
                'placeholder' => esc_html__( 'Enter your custom text', 'woolentor-pro' ),
                'default' => esc_html__( 'Order note not found!', 'woolentor-pro' ),
            ]
        );

        $this->end_controls_section();

        $this->controls_for_conditions();

        $this->start_controls_section(
            'section_order_note_heading_style',
            [
                'label' => esc_html__( 'Heading', 'woolentor-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->control_for_no_order_found_notice( 2 );

        $this->add_control(
            'order_note_heading_color',
            [
                'label' => esc_html__( 'Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'active' => false,
                ],
                'dynamic' => [
                    'active' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-order-note-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'order_note_heading',
                'global' => [
                    'active' => false,
                ],
                'exclude' => [ 'font_family', 'word_spacing' ],
                'fields_options' => [
                    'font_size' => [
                        'label' => esc_html__( 'Size (px)', 'woolentor-pro' ),
                        'size_units' => [ 'px' ],
                        'responsive' => false,
                        'default' => [
                            'unit' => 'px',
                        ],
                    ],
                    'line_height' => [
                        'label' => esc_html__( 'Line-Height (px)', 'woolentor-pro' ),
                        'size_units' => [ 'px' ],
                        'responsive' => false,
                        'default' => [
                            'unit' => 'px',
                        ],
                    ],
                    'letter_spacing' => [
                        'label' => esc_html__( 'Letter Spacing (px)', 'woolentor-pro' ),
                        'size_units' => [ 'px' ],
                        'responsive' => false,
                    ],
                ],
                'selector' => '{{WRAPPER}} .woolentor-email-order-note-heading',
            ]
        );

        $this->add_control(
            'order_note_heading_align',
            [
                'label' => esc_html__( 'Alignment', 'woolentor-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'woolentor-pro' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'woolentor-pro' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'woolentor-pro' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-order-note-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'order_note_heading_border',
                'fields_options' => [
                    'width' => [
                        'responsive' => false,
                    ],
                    'color' => [
                        'global' => [
                            'active' => false,
                        ],
                        'dynamic' => [
                            'active' => false,
                        ],
                    ],
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .woolentor-email-order-note-heading',
            ]
        );

        $this->add_control(
            'order_note_heading_border_radius',
            [
                'label' => esc_html__( 'Border Radius (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-order-note-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'order_note_heading_background',
                'label' => esc_html__( 'Background', 'woolentor-pro' ),
                'types' => [ 'classic', 'gradient' ],
                'fields_options' => [
                    'color' => [
                        'global' => [
                            'active' => false,
                        ],
                        'dynamic' => [
                            'active' => false,
                        ],
                    ],
                    'color_b' => [
                        'global' => [
                            'active' => false,
                        ],
                        'dynamic' => [
                            'active' => false,
                        ],
                    ],
                    'image' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'position' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'attachment' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'repeat' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'size' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'bg_width' => [
                        'label' => esc_html__( 'Width (px)', 'woolentor-pro' ),
                        'size_units' => [ 'px', '%' ],
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .woolentor-email-order-note-heading',
            ]
        );

        $this->add_control(
            'order_note_heading_margin',
            [
                'label' => esc_html__( 'Margin (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-order-note-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'order_note_heading_padding',
            [
                'label' => esc_html__( 'Padding (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-order-note-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_order_note_content_style',
            [
                'label' => esc_html__( 'Content', 'woolentor-pro' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->control_for_no_order_found_notice( 3 );

        $this->add_control(
            'order_note_content_color',
            [
                'label' => esc_html__( 'Color', 'woolentor-pro' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'active' => false,
                ],
                'dynamic' => [
                    'active' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-order-note-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'order_note_content',
                'global' => [
                    'active' => false,
                ],
                'exclude' => [ 'font_family', 'word_spacing' ],
                'fields_options' => [
                    'font_size' => [
                        'label' => esc_html__( 'Size (px)', 'woolentor-pro' ),
                        'size_units' => [ 'px' ],
                        'responsive' => false,
                        'default' => [
                            'unit' => 'px',
                        ],
                    ],
                    'line_height' => [
                        'label' => esc_html__( 'Line-Height (px)', 'woolentor-pro' ),
                        'size_units' => [ 'px' ],
                        'responsive' => false,
                        'default' => [
                            'unit' => 'px',
                        ],
                    ],
                    'letter_spacing' => [
                        'label' => esc_html__( 'Letter Spacing (px)', 'woolentor-pro' ),
                        'size_units' => [ 'px' ],
                        'responsive' => false,
                    ],
                ],
                'selector' => '{{WRAPPER}} .woolentor-email-order-note-content',
            ]
        );

        $this->add_control(
            'order_note_content_align',
            [
                'label' => esc_html__( 'Alignment', 'woolentor-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'woolentor-pro' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'woolentor-pro' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'woolentor-pro' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-order-note-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'order_note_content_border',
                'fields_options' => [
                    'width' => [
                        'responsive' => false,
                    ],
                    'color' => [
                        'global' => [
                            'active' => false,
                        ],
                        'dynamic' => [
                            'active' => false,
                        ],
                    ],
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .woolentor-email-order-note-content',
            ]
        );

        $this->add_control(
            'order_note_content_border_radius',
            [
                'label' => esc_html__( 'Border Radius (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-order-note-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'order_note_content_background',
                'label' => esc_html__( 'Background', 'woolentor-pro' ),
                'types' => [ 'classic', 'gradient' ],
                'fields_options' => [
                    'color' => [
                        'global' => [
                            'active' => false,
                        ],
                        'dynamic' => [
                            'active' => false,
                        ],
                    ],
                    'color_b' => [
                        'global' => [
                            'active' => false,
                        ],
                        'dynamic' => [
                            'active' => false,
                        ],
                    ],
                    'image' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'position' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'attachment' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'repeat' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'size' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'bg_width' => [
                        'label' => esc_html__( 'Width (px)', 'woolentor-pro' ),
                        'size_units' => [ 'px', '%' ],
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .woolentor-email-order-note-content',
            ]
        );

        $this->add_control(
            'order_note_content_margin',
            [
                'label' => esc_html__( 'Margin (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-order-note-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'order_note_content_padding',
            [
                'label' => esc_html__( 'Padding (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-order-note-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_wrapper_style',
            [
                'label' => esc_html__( 'Wrapper', 'woolentor-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->control_for_no_order_found_notice( 4 );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wrapper_border',
                'fields_options' => [
                    'width' => [
                        'responsive' => false,
                    ],
                    'color' => [
                        'global' => [
                            'active' => false,
                        ],
                        'dynamic' => [
                            'active' => false,
                        ],
                    ],
                ],
                'selector' => '{{WRAPPER}} .woolentor-email-order-note-wrapper',
            ]
        );

        $this->add_control(
            'wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-order-note-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_background',
                'label' => esc_html__( 'Background', 'woolentor-pro' ),
                'types' => [ 'classic', 'gradient' ],
                'fields_options' => [
                    'color' => [
                        'global' => [
                            'active' => false,
                        ],
                        'dynamic' => [
                            'active' => false,
                        ],
                    ],
                    'color_b' => [
                        'global' => [
                            'active' => false,
                        ],
                        'dynamic' => [
                            'active' => false,
                        ],
                    ],
                    'image' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'position' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'attachment' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'repeat' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'size' => [
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                    'bg_width' => [
                        'label' => esc_html__( 'Width (px)', 'woolentor-pro' ),
                        'size_units' => [ 'px', '%' ],
                        'dynamic' => [
                            'active' => false,
                        ],
                        'responsive' => false,
                    ],
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .woolentor-email-order-note-wrapper',
            ]
        );

        $this->add_control(
            'wrapper_margin',
            [
                'label' => esc_html__( 'Margin (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-order-note-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'wrapper_padding',
            [
                'label' => esc_html__( 'Padding (px)', 'woolentor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .woolentor-email-order-note-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Controls for conditions.
     */
    public function controls_for_conditions() {
        $this->start_controls_section(
            'section_conditions',
            [
                'label' => esc_html__( 'Conditions', 'woolentor-pro' ),
            ]
        );

        $this->control_for_no_order_found_notice( 5 );

        $this->add_control(
            'conditions_order_status',
            [
                'label' => esc_html__( 'Order Status', 'woolentor-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => esc_html__( 'Off', 'woolentor-pro' ),
                'label_on' => esc_html__( 'On', 'woolentor-pro' ),
            ]
        );

        $this->add_control(
            'conditions_order_statuses',
            [
                'label' => esc_html__( 'Order Statuses', 'woolentor-pro' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => woolentor_email_get_conditions_order_statuses(),
                'condition' => [
                    'conditions_order_status' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'conditions_payment_status',
            [
                'label' => esc_html__( 'Payment Status', 'woolentor-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => esc_html__( 'Off', 'woolentor-pro' ),
                'label_on' => esc_html__( 'On', 'woolentor-pro' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'conditions_payment_statuses',
            [
                'label' => esc_html__( 'Payment Statuses', 'woolentor-pro' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => woolentor_email_get_conditions_payment_statuses(),
                'condition' => [
                    'conditions_payment_status' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * No order found notice control.
     */
    public function control_for_no_order_found_notice( $serial = 1 ) {
        $order = woolentor_email_get_order();

        if ( ! is_object( $order ) || empty( $order ) ) {
            $this->add_control(
                'no_order_found_notice_html_' . $serial,
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => woolentor_email_no_order_found_notice_html(),
                    'content_classes' => 'woolentor-email-no-order-found-notice',
                    'separator' => 'after',
                ]
            );
        }
    }

    /**
     * Render order note widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! woolentor_email_widget_conditions( $settings ) ) {
            return;
        }

        $email = woolentor_email_get_email();

        if ( empty( $email ) ) {
            return;
        }

        $heading = isset( $settings['order_note_heading'] ) ? sanitize_text_field( $settings['order_note_heading'] ) : '';
        $alt = isset( $settings['order_note_alt'] ) ? sanitize_text_field( $settings['order_note_alt'] ) : '';
        $custom = isset( $settings['order_note_custom'] ) ? sanitize_textarea_field( $settings['order_note_custom'] ) : '';
        $order_note = isset( $email->customer_note ) ? sanitize_textarea_field( $email->customer_note ) : '';

        $content = '';

        if ( empty( $order_note ) ) {
            if ( 'custom' === $alt ) {
                $content = $custom;
            }
        } else {
            $content = $order_note;
        }

        if ( empty( $content ) ) {
            if ( woolentor_is_preview_mode() ) {
                $content = esc_html( 'This is some customer note , just some dummy text nothing to see here', 'woolentor-pro' );
            } else {
                return;
            }
        }

        $output = '';

        if ( ! empty( $heading ) ) {
            $output .= '<div class="woolentor-email-order-note-heading">' . $heading . '</div>';
        }

        if ( ! empty( $content ) ) {
            $output .= '<div class="woolentor-email-order-note-content">' . $content . '</div>';
        }

        if ( ! empty( $output ) ) {
            $output = '<div class="woolentor-email-order-note">' . $output . '</div>';
        }

        if ( ! empty( $output ) ) {
            $output = '<div class="woolentor-email-order-note-wrapper">' . $output . '</div>';
        }

        $output = woolentor_email_replace_placeholders_all( $output );

        echo $output;
    }
}
