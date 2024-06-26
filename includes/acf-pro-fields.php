<?php

namespace news_rss_feed_generator\acf_pro_fields;

const acf_taxonomy = "news_category";
const acf_field_repeater = "rss_fields";
const acf_field_repeater_key = "field_667c325ba8721";
const acf_field_slug = "rss_slug";
const acf_field_slug_key = "field_667c3267a8722";
const acf_field_count = "feed_count";
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
                'key' => acf_field_repeater_key,
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
                'collapsed' => acf_field_slug_key,
                'button_label' => 'Add Rss Feed',
                'rows_per_page' => 20,
                'sub_fields' => array(
                    array(
                        'key' => acf_field_slug_key,
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
                        'key' => 'field_667c830e485dc',
                        'label' => 'Number of articles to display',
                        'name' => acf_field_count,
                        'aria-label' => '',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => 10,
                        'min' => 1,
                        'max' => '',
                        'placeholder' => '',
                        'step' => '',
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

add_action('acf/save_post', __NAMESPACE__ . '\\setup_flush_rewrite_rules', 5);
function setup_flush_rewrite_rules( $post_id ) {

    $current_rss_slug = get_field(acf_field_repeater, 'option');
    $new_rss_slug = $_POST['acf'][acf_field_repeater_key];

    for ($i=0; $i < sizeof($current_rss_slug); $i++) {
        if ($current_rss_slug[$i][acf_field_slug] != $new_rss_slug["row-" . $i][acf_field_slug_key]) {
            // rows have changed. flush rewrite rules (triggered later)
            update_option( 'news-rss-feed-generator-rewrite-rules', 1 );

        } else {
            // slugs do match. no need to flush rules if only the taxonomy terms have changed
        }
    }
}

// run after nearly everything. mainly because the save_post can't flush the rules properly (the rules are flushed
// before the new rss feed is created, so the newly defined feeds are too late)
add_action( 'init', __NAMESPACE__ . '\\late_flush_rewrite', 999999 );
function late_flush_rewrite() {

    if ( ! $option = get_option( 'news-rss-feed-generator-rewrite-rules' ) ) {
        return false;
    }

    if ( $option == 1 ) {
        flush_rewrite_rules();

        // clear the option, so it doesn't flush every page load
        update_option( 'news-rss-feed-generator-rewrite-rules', 0 );

    }

    return true;

}
