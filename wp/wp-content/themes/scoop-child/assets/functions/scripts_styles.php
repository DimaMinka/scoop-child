<?php
/**
 *
 * Scripts and Styles
 *
 * @since  1.0.0
 *
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Get paths for assets
 */
class JsonManifest {
    private $manifest;
    public function __construct($manifest_path) {
        if (file_exists($manifest_path)) {
            $this->manifest = json_decode(file_get_contents($manifest_path), true);
        } else {
            $this->manifest = [];
        }
    }
    public function get() {
        return $this->manifest;
    }
    public function getPath($key = '', $default = null) {
        $collection = $this->manifest;
        if (is_null($key)) {
            return $collection;
        }
        if (isset($collection[$key])) {
            return $collection[$key];
        }
        foreach (explode('.', $key) as $segment) {
            if (!isset($collection[$segment])) {
                return $default;
            } else {
                $collection = $collection[$segment];
            }
        }
        return $collection;
    }
}
function asset_path($filename) {
    $dist_path = get_stylesheet_directory_uri() . '/dist/';
    $directory = dirname($filename) . '/';
    $file = basename($filename);
    static $manifest;
    if (empty($manifest)) {
        $manifest_path = get_stylesheet_directory() . '/dist/' . 'assets.json';
        $manifest = new JsonManifest($manifest_path);
    }
    if (array_key_exists($file, $manifest->get())) {
        return $dist_path . $directory . $manifest->get()[$file];
    } else {
        return $dist_path . $directory . $file;
    }
}

/**
 *
 * Scripts: Frontend with no conditions, Add Custom Scripts to wp_head
 *
 * @since  1.0.0
 *
 */
add_action('wp_enqueue_scripts', 'scoop_scripts', 101);
function scoop_scripts()
{
    $suffix = (!WP_DEBUG ? '.min' : '');

    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {


        //wp_enqueue_script('jquery'); // Enqueue it!
        //wp_deregister_script('jquery'); // Deregister WordPress jQuery
        //wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js', array(), '1.11.2');

        /**
         *
         * Minified and concatenated scripts
         *
         *     Order is important
         *
         */
        wp_enqueue_style('scoop/css', asset_path('styles/main.css'), false, null);
//
//        wp_enqueue_script('scoop/js', asset_path('scripts/main.js'), '', null, true);

        wp_deregister_style( 'pojo-style-rtl' );
        wp_deregister_style( 'pojo-style' );

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

    }

}


/**
 * Disable WP Features
 */
function disable_wp_features() {

    if(!is_admin()) {

        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );

        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'index_rel_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action( 'wp_head', 'feed_links_extra', 3 );
        remove_action( 'wp_head', 'feed_links', 2 );
        remove_action( 'wp_head', 'rsd_link' );
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
        remove_action('set_comment_cookies', 'wp_set_comment_cookies');

    }

}
add_action( 'init', 'disable_wp_features' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param    array  $plugins
 * @return   array             Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}



function remove_recent_comment_style() {
    global $wp_widget_factory;
    remove_action(
        'wp_head',
        array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' )
    );
}
add_action( 'widgets_init', 'remove_recent_comment_style' );
