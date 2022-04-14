<?php


/**
 * Triggered by uninstalling the plugin
 * * @package FirstPlugin
 * Always check for the constant WP_UNINSTALL_PLUGIN in uninstall.php before doing anything. 
 * This protects against direct access.
 * The constant will be defined by WordPress during the uninstall.php invocation.
 * The constant is NOT defined when uninstall is performed by register_uninstall_hook().
 */


if(!defined("WP_UNINSTALL_PLUGIN")){
    die("You can't access this file");
}

//clear database of data associated with plugin

$books = get_posts(
    array(
        "post_type" => "book",
        'numberposts'=> -1

    )
);
//can be done two ways
//delete post method
foreach($books as $book){
    wp_delete_post($book->ID, true);
}
// database access method
// global $wpdb;

// $wpdb->query("DELETE FROM wp_posts WHERE post_type='book'");
// //now find all the meta data that are associated with posts that are no long in wp_post
// //post_id would be the same as ID
// $wpdb->query("DELETE FROM wp_posts_meta WHERE post_id NOT IN (SELECT ID FROM wp_posts)");
// $wpdb->query("DELETE FROM wp_term_relationship WHERE object_id NOT IN (SELECT ID FROM wp_posts)");
