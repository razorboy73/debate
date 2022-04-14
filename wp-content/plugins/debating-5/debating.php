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

if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}






class DBTPlugin{

    var $settings, $options_page;

    function __construct()
    {

         if (is_admin()) {
			// Load example settings page
		        if (!class_exists("DBT_Settings")){
                  
		 		    require(plugin_dir_path( __FILE__ ). 'dbt-settings.php');
		            $this->settings = new DBT_Settings();	
                  
                }
		    }
        add_action('init', array($this, 'create_debatepost_type') );
        add_action("init", array($this, 'create_debate_taxonomy') );
        add_action("admin_init", array($this,"position_role_caps"));
        add_action('init', array($this, 'create_positionpost_type') );
        add_action('init', array($this, 'create_position_taxonomy') );
        add_action('comment_post', array($this, 'save_comment_meta_data') );
        add_action('admin_menu', array($this,'admin_menu') );
        add_filter('get_comment_author_link', array($this, 'attach_position_to_author') );  
        add_filter('template_include', array($this, 'template_loader') ); //add in templates
        //add_filter('comments_template', array($this, 'comments_template_loader') ); 
        
    }

    
    //add templates
    function template_loader($template){
        //identify if post is a debate post and assign template
        $file = "";
        if(is_single() && get_post_type() =="debate"){
            $file = "single-debate.php";

        }

        if($file){
            $template = plugin_dir_path( __FILE__ ).'/templates/'.$file;
        }
        return $template;
       
    }
   


   //adding administration menu
    function admin_menu(){
        add_menu_page("Debate Settings", "Debate Display",
        "manage_options","dbt-settings-page", array($this,"dbtHTML"));
    }

    function dbtHTML(){ ?>

        <div class="wrap">
            <div class="row oxd-admin-row">
                <h1><?php _e('Debate Plugin','dbt'); ?></h1>
                    <p><?php _e('This debate plugin is an easy and simple plugin to create debates on your website in four steps:','dbt'); ?></p>
                        <ol>
                            <li><?php _e('Create a debate and give a starting date and closing date.','dbt'); ?></li>
                            <li><?php _e('Create two proposals','dbt'); ?></li>
                            <li><?php _e('Go to Debate, link the proposals to the debate and publish it.','dbt'); ?></li>
                        </ol>
                    </div>
            </div>    
         <div class="wrap">
            <h1>Debate Settings</h1>
            <form action="options.php" method="POST" >
            <?php
            settings_fields("lmtplugin");
            do_settings_sections("dbt-settings-page");
            //submit_button();
           ?>
            </form>
            <div class="row oxd-admin-row">
            <h3><?php _e('Shortcode options','oxd'); ?></h3>
            <table class="form-table">
                <tr valign="top">
                    <td scope="row">
                        <p><strong><?php _e('Basic shortcode','oxd'); ?></strong></p>
                        <blockquote>[debates_q]</blockquote>
                    </td>
                </tr>
                <tr valign="top">
                    <td scope="row">
                        <p><strong><?php _e('Open debates shortcode','oxd'); ?></strong></p>
                        <p><?php _e('It lists only the open debates on your page.','oxd'); ?></p>
                        <blockquote>[debates_q type="open"]</blockquote>
                    </td>
                </tr>
                <tr valign="top">
                    <td scope="row">
                        <p><strong><?php _e('Closed debates shortcode','oxd'); ?></strong></p>
                        <p><?php _e('It lists only the closed debates on your page.','oxd'); ?></p>
                        <blockquote>[debates_q type="closed"]</blockquote>
                    </td>
                </tr>
                <tr valign="top">
                    <td scope="row">
                        <p><strong><?php _e('Coming soon debates shortcode','oxd'); ?></strong></p>
                        <p><?php _e('It lists only the debates that are coming soon.','oxd'); ?></p>
                        <blockquote>[debates_q type="soon"]</blockquote>
                    </td>
                </tr>
                <tr valign="top">
                    <td scope="row">
                        <p><strong><?php _e('Number of listed debates','oxd'); ?></strong></p>
                        <blockquote>[debates_q type="x"]</blockquote>
                    </td>
                </tr>
            </table>
            </div>
        </div>
        <?php }

function settings(){
        
    add_settings_section("deb_first_section","Optional Heading (set to Null if not needed)","optional_content", "dbt-settings-page" );
    //sets location of plugin within post
    // add_settings_field("lmt_location","Display Location (Not Being Used)",array($this,"locationHTML"),"lmt-settings-page","lmt_first_section" );
    // register_setting("lmtplugin","lmt_location", array("sanitize_callback"=>array($this,"sanitizeLocation"),"default"=>"0"));

    // //set textHeading for tutorial section
    // add_settings_field("lmt_headline","Tutorial Headline - inserts text into headline of tutorial",array($this,"headlineHTML"),"lmt-settings-page","lmt_first_section" );
    // register_setting("lmtplugin","lmt_headline", array("sanitize_callback"=>"sanitize_text_field","default"=>"Tutorial Collection"));
    // //set display as on or off
    // add_settings_field("lmt_display","Show Tutorials On All Tutorial Posts", array($this,"checkboxHTML"),"lmt-settings-page","lmt_first_section",array("theName"=>"lmt_display") );
    // register_setting("lmtplugin","lmt_display", array("sanitize_callback"=>"sanitize_text_field","default"=>"1"));
    // //set displayable via short code
    // add_settings_field("lmt_shortcode","Render Via Shortcode on individual posts", array($this,"checkboxHTML"),"lmt-settings-page","lmt_first_section",array("theName"=>"lmt_shortcode") );
    // register_setting("lmtplugin","lmt_shortcode", array("sanitize_callback"=>"sanitize_text_field","default"=>"0"));

  
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
            'show_in_rest'          => true,
            'supports'            => array('title','editor', 'author', 'thumbnail', 'excerpt', 'comments'),
            'hierarchical'        => false,//makes them pages
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
            'show_in_rest'   => true,
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
            'rewrite' => array('slug'=> "debate-topics"),
            'show_in_rest'          => true
        );

        register_taxonomy( 'debate_topics', 'debate', $args );
        
    }

    function create_positionpost_type(){
        

            $labels = array(
                'name'                => __('Positions','dbt'),
                "add_new_item"        =>__("Add New Position","dbt"),
                "edit_item" =>"Edit Your Position", 
                'singular_name'       => __('Position','dbt'),
                'menu_name'           => __('Positions','dbt'),
                'all_items'           => __('All Positions','dbt'),
                'view_item'           => __('View Position','dbt'),
                'add_new'             => __('Add Position','dbt'),
            );
       
        $args = array(
            //'label' => "Label only works with singles",
            'labels'              => $labels,
            'show_in_rest'          => true,
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
            'publicly_queryable'  => true,
            'taxonomies'          => array( 'post_tag' ),
            'has_archive'         => true,
            //'capability_type'     => "post",
            'capability_type'     => array("position","positions"),
            'map_meta_cap'        => false,
            //had to use custom capabilities to make this work
            // 'capabilities' => array(
            //      'edit_post' => 'edit_position',
            //      'edit_posts' => 'edit_positions', //Cant turn this on
            //      'edit_others_posts' => 'edit_others_positions',
            //     'publish_posts' => 'publish_positions',
            //     'read_post' => 'read_position',
            //     'read_private_posts' => 'read_private_positions',
            //     'delete_post' => 'delete_position'
            // ),

        );

        register_post_type( 'position', $args );
        
    }

   

function position_role_caps(){
    //add the roles that can administer the debate posts
    $roles = array("administrator");

    foreach($roles as $the_role){
        $role = get_role($the_role);
        $role->add_cap("read");
        $role->add_cap("read_position"); //must match post type
        $role->add_cap("read_private_position");
        $role->add_cap("edit_position");
        $role->add_cap("edit_positions");
        $role->add_cap("edit_other_position");
        $role->add_cap("edit_published_position");
        $role->add_cap("publish_position");
        $role->add_cap("publish_positions");
        $role->add_cap("delete_position");
        $role->add_cap("delete_positions");
        $role->add_cap("delete_others_position");
        $role->add_cap("delete_private_position");
        $role->add_cap("delete_published_position");
    }



}

    function create_position_taxonomy(){
        $labels = array(
            "name" => "Position Categories",//widget and main cat page
            "singular_name" => "Single Silly Debate",//used  when  viewing the category
            'search_items'      => "Search Da Debates",//in the widget
            'all_items'         => 'All Dat Debate Categories',
            'parent_item'       => 'Main Crazy Category', //category widget
            'parent_item_colon' => 'Parent Debate Category?Sdfdsfsd:',
            'name_field_description' => "Pick Wisely",
            'edit_item'         => 'Edit Position Category Name', 
            'update_item'       => 'Update The Debate Category',//shows when using quick edit
            'add_new_item'      => 'Add New Debate Topic', // 
            'new_item_name'     => 'New Groovy Debate Topic',
            //'menu_name'         => __( 'Arugment Topics' ), // overrides name in menu
        );
        $args= array(
            "labels" => $labels,

            'hierarchical' => true,
            'rewrite' => array('slug' => 'positions'),
            'show_in_rest'          => true,

        );


        register_taxonomy("position_category", "position", $args);

    }

    function save_comment_meta_data($comment_id, $ID){
       //gets comment id and debate post_id
       $comment = get_comment($comment_id);
       //find the post id associated with the comment
       $comment_post_id = $comment->comment_post_ID;

       //find the post type
       $post_type = get_post_type($comment_post_id);
       //if post is a debate post
        //

        if($post_type =="debate"){
            if ( isset($_POST['position'])){
                $args = array(
                    'post_type' => 'position',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'ignore_sticky_posts' => 1
                );

                $my_query = new WP_Query($args);

                if($my_query->have_posts()){
                    while($my_query->have_posts()): $my_query->the_post();
                    $title = html_entity_decode(get_the_title());
                    if(html_entity_decode($_POST['position'])== html_entity_decode($title)){
                            $position = get_the_ID();
                            //$color = get_post_meta($position, "position_color", true);
                        }
                endwhile;
                }
                $args = array(
                    "post_type" => "debate",
                    "post_status" => "publish",
                    "posts_per_page" => -1,
                    'ignore_sticky_posts' => 1
                );
                if($my_query->have_posts()){
                    while($my_query->have_posts()): $my_query->the_post();
                    $title = html_entity_decode(get_the_title());
                    if(html_entity_decode($_POST['position']) == html_entity_decode(get_post_meta(get_the_ID(),'positiona', true))){
                        $position_type = "A";
                    } else if ( html_entity_decode($_POST['position']) == html_entity_decode(get_post_meta(get_the_ID(),'positionb',true)) ) {

                    $position_type = 'B';

                    } else {

                        $position_type = 'OTHER';
                    }

                endwhile;
                }
            
                add_comment_meta( $comment_id, 'posture', $_POST[ 'position' ] );
                add_comment_meta( $comment_id, 'position_type', $position_type );
                


            }
        }

    


    }

    function attach_position_to_author($author){
        //connect an author with their position
        //for a given comment, find the position 
        //for a given comment, find the ppositiontype
        //if there is a position, attach approriate color code

         
        $position = get_comment_meta(get_comment_ID(),'position',true);
        $position_type = get_comment_meta(get_comment_ID(), 'position_type', true);
        //$color = position_color(get_comment_ID(),$position_type);


        if ( $position) {
            
            if (($position_type == 'A') or ($position_type == 'B')) {
            $author .= '<p class="posture-circle-container" style="color: red !important"><span class="circle-text" ></span></p><p>' . __('For Proposal ','dbt') . $position_type . '</p>';
            } else {
            $author .= '<p class="posture-circle-container" style="color: blue !important"><span class="circle-text" ></span></p><p>' . __('For Alternative Proposal','dbt') . '</p>';
                
            }
        }
            
        return $author;
        
    }

    // function position_color ($debateId,$position) {
    
    //     if ($position == 'A') {
    //         if (get_post_meta( $debateId, 'position_colour_a', true ) != '' ) {
    //             $color = get_post_meta( $debateId, 'position_colour_a', true );
    //         } else {
    //             $color = get_option('global_position_colour_a');
    //         }
            
    //         if ($color == '') {
    //             $color = '#ffd300';
    //         }
    //     }
    //     if ($position == 'B') {
    //         if (get_post_meta( $debateId, 'position_colour_b', true ) != '' ) {
    //             $color = get_post_meta( $debateId, 'position_colour_b', true );
    //         } else {
    //             $color = get_option('global_position_colour_b');
    //         }
            
    //         if ($color == '') {
    //             $color = '#79eef3';
    //         }
    //     }
        
        
    //     return $color;
        
    // } 

//add shortcode to display debate post type 
//show only published debates





}


//initialize Plugin Object



$dbtPlugin = new DBTPlugin();

add_shortcode("debates_sc", "display_debate_posts");

function display_debate_posts ($attributes){

    // return "debates short code";
    $attributes = shortcode_atts(array(
        "status" => "open",
        "post_type" => "debate",
        "post_status" => "publish"
    ), $attributes,"display_debate_posts");

    extract($attributes);


 
    switch($status){
        case "open":
            $counter = 0;
            $string ="";
            $query = new WP_Query($attributes);
            if($query->have_posts()){
                $string .= '<ul>';
                while($query->have_posts()){
                    $counter ++;

                    $query->the_post();
                    $positiona_title = get_post_meta(get_the_ID(), 'positiona', true);
                    $positiona = get_page_by_title($positiona_title, OBJECT, 'position');
                    $positiona_author = $positiona->post_author;
                    $authora_obj = get_user_by('id',  $positiona_author);
                    $authora_name = $authora_obj->display_name;

                    $positionb_title = get_post_meta(get_the_ID(), 'positionb', true);
                    $positionb = get_page_by_title($positionb_title, OBJECT, 'position');
                    $positionb_author = $positionb->post_author;
                    $authorb_obj = get_user_by('id',  $positionb_author);
                    $authorb_name = $authorb_obj->display_name;

                    $debateId = get_the_ID();
                    $string .= '<li class="even_debate_li">
                    <div class="debate-list-container debates-container">
                    <p class="shortcode-debate-title">' . get_the_title() . '</p>
                    <p class="shortcode-debate-excerpt">' . get_the_excerpt() . '</p>


                        <div class="col col-sm-6">
                        <div class="shortcode-speakera-div">
                        <p class="shortcode-speaker-title">Speaker A</p>
                        <p>' . $authora_name . '</p>
                        </div>
                        <div class="shortcode-speakerb-div">
                        <p class="shortcode-speaker-title">Speaker B</p>
                        <p>' . $authorb_name . '</p>
                        </div>
                        </div>
                        <div class="col col-sm-6 text-right duration-col">
                        <div class="shortcode-duration-container">
                        <div id="shortcode-current-percent-container">
                        </div>
                        </div>

                        <a href="' . get_permalink() . '">
                        <button class="oxd-button go-debate-button" style="background-color: green">Go ></button>
                        </a>
                        </div>

                    </div>
                </li>';
                

            }
            $string .= '</ul>';
        }
        break;

     default:
            
        $counter = 0;
        $string = '';
        $query = new WP_Query( $args );

        if( $query->have_posts() ){
            $string .= '<ul">';
            while( $query->have_posts() ){ 
                
                // if ($number == $counter) {
                //     break;
                // }
                
                $counter ++;

                $query->the_post();
                //find the title using 
                $positiona_title = get_post_meta( get_the_ID(), 'positiona', true );

                $positiona = get_page_by_title( $positiona_title, OBJECT, 'position' );
                $positiona_author = $positiona->post_author; 
                $authora_obj = get_user_by('id', $positiona_author);
                $authora_name = $authora_obj->display_name;

                $positionb_title = get_post_meta( get_the_ID(), 'positionb', true );
                $positionb = get_page_by_title( $positionb_title, OBJECT, 'position' );
                $positionb_author = $positionb->post_author; 
                $authorb_obj = get_user_by('id', $positionb_author);
                $authorb_name = $authorb_obj->display_name;

                $debateId = get_the_ID();
                //$debate_duration = get_debate_duration($debateId);

                //if (!$debate_duration['disabled']) {
                $string .= '<li>
                                  <div class="debate-list-container debates-container">
                                    <p class="shortcode-debate-title">' . get_the_title() . '</p>
                                    <p class="shortcode-debate-excerpt">' . get_the_excerpt() . '</p>


                                        <div class="col col-sm-6">
                                        <div class="shortcode-speakera-div">
                                        <p class="shortcode-speaker-title">Speaker A</p>
                                        <p>' . $authora_name . '</p>
                                        </div>
                                        <div class="shortcode-speakerb-div">
                                        <p class="shortcode-speaker-title">Speaker B</p>
                                        <p>' . $authorb_name . '</p>
                                        </div>
                                        </div>
                                        <div class="col col-sm-6 text-right duration-col">
                                        <div class="shortcode-duration-container">
                                        <div id="shortcode-current-percent-container">
                                        </div>
                                        </div>

                                        <a href="' . get_permalink() . '">
                                        <button class="oxd-button go-debate-button" style="background-color:' . $debate_colour . '">Go ></button>
                                        </a>
                                        </div>

                                  </div>
                                </li>';
                //} 


            }
            $string .= '</ul>';
        }
        break;
    }
    wp_reset_query();
    return $string;
}

function dbt_threaded_comments(){
    if (!is_admin()) {
         if (is_singular() && comments_open() && (get_option('thread_comments') == 1))
              wp_enqueue_script('comment-reply');
         }
    }
    
    add_action('get_header', 'dbt_threaded_comments');
    

// Remove Featured Image Metabox from Custom Post Type Edit Screens
// function remove_image_box() {
//     remove_meta_box('postimagediv','debate','side');
//     remove_meta_box('postimagediv','posture','side');
 
//  }
//  add_action('do_meta_boxes', 'remove_image_box');



function add_theme_caps() {
    // administrator
    // $administrator = get_role( 'administrator' );
        
    // $administrator->add_cap( 'edit_debates' );
    // $administrator->add_cap( 'edit_debate' );
    // $administrator->add_cap( 'publish_debates' ); 
    // $administrator->add_cap( 'delete_debate' );
    // $administrator->add_cap( 'read_debate' );
    // $administrator->add_cap( 'edit_others_debates' ); 
    // $administrator->add_cap( 'read_private_debates' );
        
    // $administrator->add_cap( 'edit_positions' );
    // $administrator->add_cap( 'edit_position' );
    // $administrator->add_cap( 'publish_positions' ); 
    // $administrator->add_cap( 'delete_position' );
    // $administrator->add_cap( 'read_position' );
    // $administrator->add_cap( 'edit_others_positions' );
    // $administrator->add_cap( 'read_private_positions' );
// editors
    $editor = get_role( 'editor' );
        
    $editor->add_cap( 'edit_debates' );
    $editor->add_cap( 'edit_debate' );
    $editor->add_cap( 'publish_debates' ); 
    $editor->add_cap( 'delete_debate' );
    $editor->add_cap( 'read_debate' );
    $editor->add_cap( 'edit_others_debates' ); 
    $editor->add_cap( 'read_private_debates' );
        
    $editor->add_cap( 'edit_positions' );
    $editor->add_cap( 'edit_position' );
    $editor->add_cap( 'publish_positions' ); 
    $editor->add_cap( 'delete_position' );
    $editor->add_cap( 'read_position' );
    $editor->add_cap( 'edit_others_positions' );
    $editor->add_cap( 'read_private_positions' );

    // authors
    $authors = get_role( 'author' );
        
    $authors->add_cap( 'edit_debates' );
    $authors->add_cap( 'edit_debate' );
    $authors->add_cap( 'publish_debates' ); 

    $authors->remove_cap( 'delete_debate' );
    $authors->remove_cap( 'read_debate' );
    $authors->remove_cap( 'edit_others_debates' ); 
    $authors->remove_cap( 'read_private_debates' );
        
    $authors->add_cap( 'edit_positions' );
    $authors->add_cap( 'edit_position' );
    $authors->add_cap( 'publish_positions' ); 

    $authors->remove_cap( 'delete_position' );
    $authors->remove_cap( 'read_position' );
    $authors->remove_cap( 'edit_others_positions' );
    $authors->remove_cap( 'read_private_positions' );
         
    }
add_action( 'admin_init', 'add_theme_caps' );

//add debate and poistion page types
function add_dbt_custom_post_types($query){
    if(is_tag() && $query->is_main_query()){
        // gets all post types:
        $post_types = get_post_types();
        $query->set( 'post_type', $post_types );
    }
}
add_filter( 'pre_get_posts', 'add_dbt_custom_post_types' );