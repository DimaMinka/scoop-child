<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Neat
 */


/**
 * Remove image medium size
 *
 */
add_filter('intermediate_image_sizes_advanced', 'cubiq_filter_image_sizes');
function cubiq_filter_image_sizes ($sizes) {
	unset( $sizes['medium'] );
	unset( $sizes['medium_large'] );

	return $sizes;
}

/**
 * Add file support for media
 *
 */
function svg_myme_types($mime_types){
	$mime_types['svg'] = 'image/svg'; //Adding svg extension
	return $mime_types;
}
add_filter('upload_mimes', 'svg_myme_types', 1, 1);
