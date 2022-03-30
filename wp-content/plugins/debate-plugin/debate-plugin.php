<?php
/*
Plugin Name: Debates
Description:  Creates a debate platform for WordPress
Version: 1.0
Author: Josh Kerbel
License: GPL2

*/


//Prevent Direct Access
defined("ABSPATH") or die();

//Register user role when activating plugin

register_activation_hook(__FILE__, "deb_activation");

//Remove  user role on deacivation

register_deactivation_hook(__FILE__, "deb_deactiviation");

function deb_activation(){
    $caps = array( 
        'read'          => true,  
        'create_post'    => true, // Allows user to create new posts
        'edit_post'      => true 
    );
    add_role( 'Debater', 'Debater', $caps );
}

function deb_deactiviation(){
    remove_role("Debater");

}
// 


add_action("init","debate_post_types");
function debate_post_types(){
    //Set up the debate post type
            register_post_type("debates", array(
                //for the archive, it uses the register post type name parameter, not the label
                //or url rewrite
                "has_archive" => true,
                'menu_position' => 30,
                'label'  => 'Debates',
                "public" => true,
                "show_in_rest" => true,
                "menu_icon" => "dashicons-format-chat",
                "description" => "A list of debates",
               
                "labels" => array(
                    "name" => esc_html("Debates","debate-plug"),
                    "add_new_item" => esc_html("Add New Debate","debate-plug"),
                    "add_new" => esc_html("Add New","debate-plug"),
                    "edit" => esc_html("Edit", "debate-plug"),
                    "edit_item" => esc_html("Edit Your Debate", "debate-plug"),
                    "new_item" => esc_html("New Debate", "debate-plug"),
                    "view" => esc_html("View Debates", "debate-plug"),
                    "view_item" => esc_html("View Debate", "debate-plug"),
                    "all_items" => esc_html("All Debates", "debate-plug"),
                    "search_items" => esc_html("Search Debates", "debate-plug"),
                    "not_found" => esc_html("No Debates Found", "debate-plug"),
                    "not_found_in_trash" => esc_html("No Debates Found in Trash", "debate-plug"),
                    "singular_name" => esc_html("Debate","debate-plug")
                ),
                //'rewrite' => array( 'slug' => 'intense-debates' ),
                "supports" => array(
                    "title",
                    "editor",
                    "excerpt"
                ),
                
                "capability_type" => array("debate","debates"),
                "map_meta_cap" => false,
                "hierarchical" => true
                )
            );
    }
    
//add role capabilities

add_action("admin_init", "deb_role_caps", 999);

function deb_role_caps(){
    //add the roles that can administer the debate posts
    $roles = array("administrator", "Debater");

    foreach($roles as $the_role){
        $role = get_role($the_role);
        $role->add_cap("read");
        $role->add_cap("read_debates"); //must match post type
        $role->add_cap("read_private_debates");
        $role->add_cap("edit_debates");
        $role->add_cap("edit_other_debates");
        $role->add_cap("edit_published_debates");
        $role->add_cap("publish_debates");
        $role->add_cap("delete_others_debates");
        $role->add_cap("delete_private_debates");
        $role->add_cap("delete_published_debates");
    }



}

//Set up debate Topic Heirarchy 

function register_debate_taxonomy() {
    $args = array( 
        'hierarchical' => true,
        'label' => 'Debate Topics',
    );
    register_taxonomy( 'debate-topics', 'debate', $args );
}
add_action( 'init', 'register_debate_taxonomy' );