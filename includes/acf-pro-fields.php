<?php

namespace news_rss_feed_generator\acf_pro_fields;

const acf_taxonomy = "news_category";
const acf_field_repeater = "rss_fields";
const acf_field_slug = "rss_slug";
const acf_field_terms = "news_terms";

// add option/settings page
add_action( 'acf/init', function() {
    acf_add_options_page( array(
        'page_title' => 'News RSS Feed Generator',
        'menu_slug' => 'news-rss-feed-generator',
        'parent_slug' => 'options-general.php',
        'position' => '',
        'redirect' => false,
    ) );
} );

// add ACF Fields to option page
add_action( 'acf/include_fields', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( array(
        'key' => 'group_667c325af0093',
        'title' => 'RSS Feed Generator',
        'fields' => array(
            array(
                'key' => 'field_667c35ed30cc0',
                'label' => 'Instructions',
                'name' => '',
                'aria-label' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_667c325ba8721',
                            'operator' => '!=empty',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => 'RSS feeds are visible at https://yoursite.com/feed/<b>RSS-SLUG</b>',
                'new_lines' => 'wpautop',
                'esc_html' => 0,
            ),
            array(
                'key' => 'field_667c325ba8721',
                'label' => 'RSS Fields',
                'name' => acf_field_repeater,
                'aria-label' => '',
                'type' => 'repeater',
                'instructions' => 'Click "Add Rss Feed" to create a new RSS feed, based on the url and news terms you want',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
                'pagination' => 0,
                'min' => 0,
                'max' => 0,
                'collapsed' => 'field_667c3267a8722',
                'button_label' => 'Add Rss Feed',
                'rows_per_page' => 20,
                'sub_fields' => array(
                    array(
                        'key' => 'field_667c3267a8722',
                        'label' => 'RSS Slug',
                        'name' => acf_field_slug,
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => 'custom-slug',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'parent_repeater' => 'field_667c325ba8721',
                    ),
                    array(
                        'key' => 'field_667c33c3a8725',
                        'label' => 'News Terms',
                        'name' => acf_field_terms,
                        'aria-label' => '',
                        'type' => 'taxonomy',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'taxonomy' => acf_taxonomy,
                        'add_term' => 0,
                        'save_terms' => 0,
                        'load_terms' => 0,
                        'return_format' => 'id',
                        'field_type' => 'checkbox',
                        'bidirectional' => 0,
                        'multiple' => 0,
                        'allow_null' => 0,
                        'bidirectional_target' => array(
                        ),
                        'parent_repeater' => 'field_667c325ba8721',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'news-rss-feed-generator',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
    ) );
} );
