<?php
/**
 *
 * Plugin Name: Debating Platform

 * Description: The online version of the Oxford-style debates adapte the physical model and makes it possible to expand 
 * the capabilities of both speakers and audience. The speakers may argue using web connectivity and multimedia, 
 * and the audience can also comment fixing its position on the proposals of the speakers or raising their own alternatives.
 * Version: 1
 * Author: Josh Kerbel
 * Text Domain: oxd
 * Domain Path: /languages/
 *
 **/

defined("ABSPATH") or die();



class DBTPlugin{

    function __construct()
    {
        add_action('init', array($this, 'create_debatepost_type') );
        add_action("init", array($this, 'create_debate_taxonomy') );
        add_action('init', array($this, 'create_positionpost_type') );
    }

   


    function create_debatepost_type() {

        $labels = array(
            'name'                => __('Debates','dbt'),
            'singular_name'       => __('Debate','dbt'),
            'menu_name'           => __('Debates','dbt'),
            'all_items'           => __('All Debates','dbt'),
            'view_item'           => __('View Debate','dbt'),
            'add_new'             => __('Add Debate','dbt'),
            'parent_item_colon'   => '',
        );
        $args = array(
            'labels'              => $labels,
            'supports'            => array('title','editor', 'author', 'thumbnail', 'excerpt', 'comments'),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'rewrite'             => array( 'slug' => 'debates'),
            'menu_icon'           => "dashicons-format-chat",//plugins_url( '/img/oxd_icon.png', __FILE__ ),
            'menu_position'       => 7,
            'taxonomies'          => array( 'post_tag' ),
            'has_archive'         => true,
            'publicly_queryable'  => true,
            'capability_type'     => array('debate','debates'),
            'map_meta_cap'        => true,
            'capabilities' => array(
                        'edit_post' => 'edit_debate',
                        'edit_posts' => 'edit_debates',
                        'edit_others_posts' => 'edit_others_debates',
                        'publish_posts' => 'publish_debates',
                        'read_post' => 'read_debate',
                        'read_private_posts' => 'read_private_debates',
                        'delete_post' => 'delete_debate'
            ),
        );

        register_post_type( 'debate', $args );
        //flush_rewrite_rules();
        
    }


    function create_debate_taxonomy(){

        $labels = array(
            "name" => "Mulitiple Debates",
            "singular_name" => "Single Silly Debate",//used  when  viewing the category
            'search_items'      => "Search Da Debates",//in the widget
            'all_items'         => 'All Dat Debate Categories',
            'parent_item'       => 'Main Crazy Category', //category widget
            'parent_item_colon' => 'Parent Debate Category?Sdfdsfsd:',
            'name_field_description' => "Pick Wisely",
            'edit_item'         => 'Edit Debate Category Name', 
            'update_item'       => 'Update The Debate Category',//shows when using quick edit
            'add_new_item'      => 'Add New Debate Topic', // 
            'new_item_name'     => 'New Groovy Debate Topic',
            'menu_name'         => __( 'Arugment Topics' ), // overrides name in menu
        );
        
        $args = array(

            "labels" => $labels,
            'hierarchical' => true,
            'rewrite' => array('slug'=> "debate-topics")
        );

        register_taxonomy( 'debate_topics', 'debate', $args );
        
    }

    function create_positionpost_type(){
        

            $labels = array(
                'name'                => __('Postures','dbt')

            );

       
        $args = array(
            'labels'              => $labels,
            'supports'            => array('title','editor', 'author', 'thumbnail', 'excerpt'),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'rewrite'             => array( 'slug' => 'positions'),
            'menu_position'       => 8,
            'menu_icon'      => "dashicons-format-status",
            
        );

        register_post_type( 'position', $args );
        
    }

}

   
$dbtPlugin = new DBTPlugin();
