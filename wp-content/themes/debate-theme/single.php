<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package debate
 */

get_header();


add_post_meta(115,"fruit","apple", false);
add_post_meta(115,"fruit","grape", false);
add_post_meta(115,"fruit","liver", false);



// print_r(get_post_meta(115, "fruit", ));

 //delete_post_meta(115, 'fruit','liver');

// print_r(get_post_meta(115, "fruit", ));

update_post_meta(115,"fruit","penis","apple");

get_footer();
