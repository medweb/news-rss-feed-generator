<?php

namespace news_rss_feed_generator\rss_endpoints;

const post_type = "news";
const posts_per_page = 10;

add_action('init', __NAMESPACE__ . '\\register_news_rss_feeds');

function register_news_rss_feeds() {

    while( have_rows(\news_rss_feed_generator\acf_pro_fields\acf_field_repeater, 'option') ) {
        the_row();
        $rss_slug = get_sub_field(\news_rss_feed_generator\acf_pro_fields\acf_field_slug);
        if ($rss_slug) {
            add_feed(
                $rss_slug,
                function() use ($rss_slug) {
                    generate_news_rss_feeds($rss_slug);
                }
            );
//            echo 'added ' . $rss_slug;
//            die();
        }
    }

}

function generate_news_rss_feeds($slug) {

    $rss_terms = array();
    $count = posts_per_page;
    // go through the list of custom rss feeds until we find our slug, then loop through all the terms we want to add
    while( have_rows(\news_rss_feed_generator\acf_pro_fields\acf_field_repeater, 'option') ) {
        the_row();
        $rss_slug = get_sub_field(\news_rss_feed_generator\acf_pro_fields\acf_field_slug);
        if ($rss_slug === $slug) {
            // this is our slug. get all the taxonomy terms to use in wp_query
            $rss_terms = get_sub_field(\news_rss_feed_generator\acf_pro_fields\acf_field_terms);
            $count = get_sub_field(\news_rss_feed_generator\acf_pro_fields\acf_field_count);
        }


    }


    $args = array(
        'post_type' => post_type, // or any custom post type
        'posts_per_page' => $count, // Number of posts in the feed
        'tax_query' => array(
            array(
                'taxonomy' => \news_rss_feed_generator\acf_pro_fields\acf_taxonomy,
                'field'    => 'term_id',
                'terms'    => $rss_terms,
            ),
        ),
    );

    $query = new \WP_Query($args);
    header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
    echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
    ?>
    <rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
        <channel>
            <title><?php bloginfo_rss('name'); ?> - Custom ACF RSS Feed</title>
            <link><?php bloginfo_rss('url') ?></link>
            <description><?php bloginfo_rss('description'); ?></description>
            <language><?php echo get_option('rss_language'); ?></language>
            <lastBuildDate><?php echo date('r'); ?></lastBuildDate>
            <?php while($query->have_posts()) : $query->the_post(); ?>
                <item>
                    <title><?php the_title_rss(); ?></title>
                    <link><?php the_permalink_rss(); ?></link>
                    <guid isPermaLink="true"><?php the_permalink_rss(); ?></guid>
                    <pubDate><?php echo get_the_date('r'); ?></pubDate>
                    <description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
                    <content:encoded><![CDATA[<?php the_content_feed('rss2'); ?>]]></content:encoded>
                </item>
            <?php endwhile; wp_reset_postdata(); ?>
        </channel>
    </rss>
    <?php
}
