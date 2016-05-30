<?php
/**
 * Neat functions and definitions
 *
 * @package Neat
 */

/**
 *
 * Paths
 *
 * @since  1.0
 *
 */
if ( !defined( 'SCOOP_THEME_DIR' ) ){
    define('SCOOP_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template());
}


/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1000; /* pixels */
}

if ( ! function_exists( 'scoop_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function scoop_setup() {

	// This theme uses wp_nav_menu() in one location.
//	register_nav_menus( array(
//		'primary'       => esc_html__( 'Primary Menu', 'scoop' )
//	) );
    
}
endif; // scoop_setup
add_action( 'after_setup_theme', 'scoop_setup' );


/**
 * Styles and scripts
 *
 * @since 1.0.0
 */
if (file_exists(dirname(__FILE__).'/assets/functions/scripts_styles.php')) {
    require_once( dirname(__FILE__).'/assets/functions/scripts_styles.php' );
}


/**
 * Widgets
 *
 * @since 1.0.0
 */
if (file_exists(dirname(__FILE__).'/assets/functions/widgets.php')) {
    require_once( dirname(__FILE__).'/assets/functions/widgets.php' );
}


/**
 * Extras: Custom functions that act independently of the theme templates.
 *
 * @since 1.0.0
 */
if (file_exists(dirname(__FILE__).'/assets/functions/extras.php')) {
    require_once( dirname(__FILE__).'/assets/functions/extras.php' );
}


/**
 * Template Functions for this theme.
 *
 * @since 1.0.0
 */
if (file_exists(dirname(__FILE__).'/assets/functions/template_functions.php')) {
    require_once( dirname(__FILE__).'/assets/functions/template_functions.php' );
}


/**
 * Custom customizer for this theme.
 *
 * @since 1.0.0
 */
if (file_exists(dirname(__FILE__).'/assets/functions/customizer.php')) {
    require( dirname(__FILE__).'/assets/functions/customizer.php' );
}

