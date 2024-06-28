<?php
/*
Plugin Name: News RSS Feed Generator
Plugin URI: https://github.com/medweb/news-rss-feed-generator
Description: WordPress Plugin for creating rss feeds based on multiple taxonomy terms
Version: 1.1.0
Author: Stephen Schrauger
Author URI: https://github.com/medweb/news-rss-feed-generator
License: GPL2
*/


namespace news_rss_feed_generator;

if ( ! defined( 'WPINC' ) ) {
    die;
}

include plugin_dir_path( __FILE__ ) . 'includes/acf-pro-fields.php';
include plugin_dir_path( __FILE__ ) . 'includes/rss-endpoints.php';
