<?php
/*
Plugin Name:  Disallow Indexing
Description:  Disallow indexing of your site on non-production environments.
Version:      1.0.0
License:      MIT License
*/
if (!is_admin()) {
    add_action('pre_option_blog_public', '__return_zero');
}