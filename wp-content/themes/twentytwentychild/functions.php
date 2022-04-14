<?php

function enqueue_twenty_twenty_stylesheet(){
    // wp_enqueue_style(
    // "twentyfifteen",
    // get_template_directory_uri()."/style.css"
    // );
    wp_enqueue_style(
        "parent-style",
        get_template_directory_uri()."/style.css" //Always returns the root URL of the parent theme, 
        //whether or not there’s a child theme running. 
        //Useful for building pieces of a parent theme that you don’t want to be easily overridden by the child theme.
        );
        wp_enqueue_style(
            "child-style",
            get_stylesheet_uri(),array("parent-style") //return the URL for the style.css of the current theme.
            //array is the dependancy
            //loads after the parent
            //CSS selector overrides the parent
            );
            
    }
add_action("wp_enqueue_scripts","enqueue_twenty_twenty_stylesheet");


add_action('init', 'create_pressreleasepost_type' );
function create_pressreleasepost_type() {

    $labels = array(
        'name'                => "Press_releases",
        'singular_name'       => "Press_release",
        'menu_name'           => "Press_releases",
        'all_items'           => "All Press_releases",
        'view_item'           => "View Press_release",
        'add_new'             => "Add Press_release",
        'parent_item_colon'   => '',
    );
    $args = array(
        'labels'              => $labels,
        'show_in_rest'          => true,
        'supports'            => array('title','editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'hierarchical'        => true,//false makes them pages
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'rewrite'             => array( 'slug' => 'press'),
        'menu_icon'           => "dashicons-format-chat",//plugins_url( '/img/oxd_icon.png', __FILE__ ),
        // 'menu_position'       => 7,
        'taxonomies'          => array( 'post_tag' ),
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'capability_type'     => array('press_release','press_releases'),
        'map_meta_cap'        => true,
        'capabilities' => array(
                    'edit_post' => 'edit_press_release',
                    'edit_posts' => 'edit_press_releases',
                    'edit_others_posts' => 'edit_others_press_releases',
                    'publish_posts' => 'publish_press_releases',
                    'read_post' => 'read_press_release',
                    'read_private_posts' => 'read_private_press_releases',
                    'delete_post' => 'delete_press_release'
        ),
    );

    register_post_type( 'press_release', $args );
    //flush_rewrite_rules();
    
}