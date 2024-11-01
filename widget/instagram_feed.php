<?php

class Elementor_Widget_migaSocialPhotoFeed_feed extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'miga_social_photo_feed';
    }

    public function get_title()
    {
        return __('Social photo feed', 'miga_social_photo_feed');
    }

    public function get_icon()
    {
        return 'eicon-image';
    }

    public function get_categories()
    {
        return [ 'general' ];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
              'label' => __('Images', 'miga_social_photo_feed'),
              'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'number_of_images',
            [
                'label' => esc_html__('Amount', 'miga_social_photo_feed'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => -1,
                'max' => 100,
                'step' => 1,
                'default' => -1,
            ]
        );



        $this->add_responsive_control(
            'wrap_images',
            [
                'label' => esc_html__('Wrap images', 'miga_social_photo_feed'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'miga_social_photo_feed'),
                'label_off' => esc_html__('Hide', 'miga_social_photo_feed'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_responsive_control("image_height", [
            "type" => \Elementor\Controls_Manager::SLIDER,
            "label" => esc_html__("Image height", "miga_social_photo_feed"),
            "range" => [
                "px" => [
                    "min" => 0,
                    "max" => 1000,
                ],
            ],
            "default" => [
                "unit" => "px",
                "size" => 300,
            ],
            "size_units" => ["px", "em", "%", "custom"],
            "devices" => ["desktop", "tablet", "mobile"],

            "selectors" => [
                "{{WRAPPER}} .miga_insta_feed a img" =>
                    "height: {{SIZE}}{{UNIT}};",
            ],
        ]);

        $this->add_responsive_control("image_width", [
            "type" => \Elementor\Controls_Manager::SLIDER,
            "label" => esc_html__("Image width", "miga_social_photo_feed"),
            "range" => [
                "px" => [
                    "min" => 0,
                    "max" => 1000,
                ],
            ],
            "default" => [
                "unit" => "%",
                "size" => 100,
            ],
            "size_units" => ["px", "em", "%", "custom"],
            "devices" => ["desktop", "tablet", "mobile"],

            "selectors" => [
                "{{WRAPPER}} .miga_insta_feed .miga_insta_feed_item_all" =>
                    "max-width: {{SIZE}}{{UNIT}};",
            ],
        ]);


        $this->end_controls_section();

        $this->start_controls_section(
            'content_button',
            [
                  'label' => __('Button', 'miga_social_photo_feed'),
                  'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
        );

        $this->add_responsive_control(
            'show_button',
            [
                'label' => esc_html__('Show button', 'miga_social_photo_feed'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'miga_social_photo_feed'),
                'label_off' => esc_html__('Hide', 'miga_social_photo_feed'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'button_text',
            [
            'label' => esc_html__('Button text', 'miga_social_photo_feed'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__('more', 'miga_social_photo_feed'),
        'placeholder' => esc_html__('Type your title here', 'miga_social_photo_feed'),
            'condition' => ['show_button' => 'yes'],
          ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'content_text',
            [
              'label' => __('Text', 'miga_social_photo_feed'),
              'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'show_text',
            [
                'label' => esc_html__('Show text', 'miga_social_photo_feed'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'miga_social_photo_feed'),
                'label_off' => esc_html__('Hide', 'miga_social_photo_feed'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_responsive_control(
            'show_text_hover',
            [
                'label' => esc_html__('Text on hover', 'miga_social_photo_feed'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'miga_social_photo_feed'),
                'label_off' => esc_html__('Hide', 'miga_social_photo_feed'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
            'show_text' => 'yes',
        ],
            ]
        );



        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_image',
            [
              'label' => __('Image', 'miga_social_photo_feed'),
              'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_gap',
            [
              'label' => esc_html__('Gap', 'textdomain'),
              'type' => \Elementor\Controls_Manager::SLIDER,
              'selectors' => [
					'{{WRAPPER}} .miga_insta_feed ' => 'gap: {{SIZE}}px',
				],
            ]
        );


        $this->add_responsive_control("image_padding", [
            "type" => \Elementor\Controls_Manager::DIMENSIONS,
            "label" => esc_html__("Padding", "miga_social_photo_feed"),
            "size_units" => ["px", "em", "%", "custom"],
            "selectors" => [
                "{{WRAPPER}} .miga_insta_feed .miga_insta_feed_link" =>
                    "padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
            ],
        ]);

        $this->add_responsive_control("image_margin", [
            "type" => \Elementor\Controls_Manager::DIMENSIONS,
            "label" => esc_html__("Item margin", "miga_social_photo_feed"),
            "size_units" => ["px", "em", "%", "custom"],
            "selectors" => [
                "{{WRAPPER}} .miga_insta_feed .miga_insta_feed_link" =>
                    "margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_font',
            [
              'label' => __('Text', 'miga_social_photo_feed'),
              'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .miga_insta_feed_text',
            ]
        );
        $this->add_control(
            'text_color',
            [
                    'label' => esc_html__('Text Color', 'textdomain'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .miga_insta_feed_text' => 'color: {{VALUE}}',
                    ],
                ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'label' => esc_html__('Margin', 'textdomain'),
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .miga_insta_feed_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'label' => esc_html__('Padding', 'textdomain'),
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .miga_insta_feed_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_button',
            [
              'label' => __('Button', 'miga_social_photo_feed'),
              'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btn_padding',
            [
        'label' => esc_html__('Padding', 'miga_social_photo_feed'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'condition' => ['show_button' => 'yes'],
        'size_units' => [ 'px', '%', 'em' ],
        'selectors' => [
          '{{WRAPPER}} .miga_insta_feed_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
        );

        $this->add_control(
            'top_margin_btn',
            [
                'label' => esc_html__('Top margin', 'miga_social_photo_feed'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .miga_insta_feed_button' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'style_tabs',
            ['condition' => ['show_button' => 'yes']]
        );

        $this->start_controls_tab(
            'style_normal_tab',
            [
                        'label' => esc_html__('Normal', 'miga_social_photo_feed'),
                    ]
        );


        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'miga_social_photo_feed'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .miga_insta_feed_button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
              'name' => 'background',
              'label' => esc_html__('Background', 'miga_social_photo_feed'),
              'types' => [ 'classic', 'gradient' ],
              'selector' => '{{WRAPPER}} .miga_insta_feed_button',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => esc_html__('Border', 'miga_social_photo_feed'),
                'selector' => '{{WRAPPER}} .miga_insta_feed_button',
            ]
        );

        $this->add_control(
            'btn_border_radius',
            [
                'label' => esc_html__('Border radius', 'miga_social_photo_feed'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .miga_insta_feed_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_hover_tab',
            [
                'label' => esc_html__('Hover', 'miga_social_photo_feed'),
            ]
        );

        $this->add_control(
            'title_color_hover',
            [
            'label' => esc_html__('Title Color', 'miga_social_photo_feed'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .miga_insta_feed_link:hover .miga_insta_feed_button' => 'color: {{VALUE}}',
            ],
        ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
          'name' => 'background_hover',
          'label' => esc_html__('Background', 'miga_social_photo_feed'),
          'types' => [ 'classic', 'gradient' ],
          'selector' => '{{WRAPPER}} .miga_insta_feed_link:hover .miga_insta_feed_button',
        ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
            'name' => 'border_hover',
            'label' => esc_html__('Border', 'miga_social_photo_feed'),
            'selector' => '{{WRAPPER}} .miga_insta_feed_link:hover .miga_insta_feed_button',
        ]
        );

        $this->add_control(
            'btn_border_radius_hover',
            [
    'label' => esc_html__('Border radius', 'miga_social_photo_feed'),
    'type' => \Elementor\Controls_Manager::DIMENSIONS,
    'size_units' => [ 'px', '%', 'em' ],
    'selectors' => [
      '{{WRAPPER}} .miga_insta_feed_link:hover .miga_insta_feed_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    ],
  ]
        );

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render()
    {
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();
        $amount = $settings["number_of_images"];
        $btnText = $settings["button_text"];
        $showBtn = ($settings["show_button"] == "yes");
        $showText = ($settings["show_text"] == "yes");
        $hoverClass = ($settings["show_text_hover"] == "yes") ? 'hover' : '';
        $wrap = ($settings["wrap_images"] == "yes") ? ' miga_insta_feed_wrap ' : '';
        $args = array(
            'post_mime_type' => 'image',
            'numberposts'    => (int) $amount,
            'post_type'      => 'attachment' ,
            'meta_query' => array(
                array(
                    'key' => 'insta_image_type',
                    'value' => 'instagram',
                    'compare' => '=',
                )
            )
         );

        $attached_images = get_children($args);
        ?>
        <style>
        .miga_insta_feed_text {
          height: calc(100% - <?php echo esc_html($settings["image_height"]["size"].$settings["image_height"]["unit"]); ?>);
        }
        </style>
        <?php
        echo '<div class="miga_insta_feed '.esc_html($wrap).'">';
        foreach ($attached_images as $image) {
            $link = get_post_meta($image->ID, "insta_url");
            $text = $image->post_content;
            $outputClass = ($showText && $showBtn) ? ' button_and_text ' : '';

            echo '<div class="miga_insta_feed_item_all">';
            echo '<div class="miga_insta_feed_item '.$hoverClass.'">';
            echo '<a title="Instagram" alt="'. esc_html(get_bloginfo("title")).' instagram" class="miga_insta_feed_link '.esc_html($outputClass).'" href="'.esc_html($link[0]).'" target="_blank">';
            echo wp_get_attachment_image($image->ID, array(300,300));

            if ($showText) {
                echo '<div class="miga_insta_feed_text">'.wp_kses_post($text).'</div>';
            }
            echo '</a>';
            echo '</div>';
            if ($showBtn) {
                echo '<div class="miga_insta_feed_button_row"><button class="miga_insta_feed_button">'.esc_html($btnText).'</button></div>';
            }
            echo '</div>';
        }
        echo '</div>';
    }
}
