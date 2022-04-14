<?php
/**
 *
 * Plugin Name: Debating Project

 * Description: Capstone project
 * Version: 1
 * Author: Josh Kerbel

 * Domain Path: /languages/
 *
 **/




 class DebateProject{

    function __construct(){
    
    add_action("admin_init", array($this,"settings"));
    add_action("admin_menu", array($this,"adminPage"));
    add_action('init', array($this, 'create_debatepost_type') );
    
    }
    function settings(){
        
        add_settings_section("dbt_first_section","Optional Heading (set to Null if not needed)",array($this,"optional_content"), "dbt-settings-page" );
        // //sets location of plugin within post
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
    function optional_content(){
        echo "Optional Content - could be set to null.  If this is inside a class, remember to use (array(($)this,callback)) to use function";
    }

    function adminPage(){
        add_menu_page("Debate Configuration Page", "Debate Configs",
        "manage_options","dbt-settings-page", array($this,"dbtHTML" ));
    }

    function dbtHTML(){ ?>

        <div class="wrap">
            <div class="row ">
                <h1><?php _e('Debate Plugin','dbt'); ?></h1>
                    <p><?php _e('This debate plugin is an easy and simple plugin to create debates on your website in four steps:','dbt'); ?></p>
                        <ol>
                            <li><?php _e('Create a debate and give a starting date and closing date.','dbt'); ?></li>
                            <li><?php _e('Create two proposals','dbt'); ?></li>
                            <li><?php _e('Go to Debate, link the proposals to the debate and publish it.','dbt'); ?></li>
                        </ol>
             </div>
             <div class="row">
                 <h2>Debates</h2>
                <ul>
                <?php
                $args = (array(
                    "post_type" => "debates",
                    "post_status" => "publish",
                    'posts_per_page' => -1,
                    'ignore_sticky_posts' => 1
                    ));
                    $string ="";
                    $query = new WP_Query($args);
                    if($query->have_posts()){
                        while($query->have_posts()){
                            $query->the_post();

                            $title = $query->the_title();


                        $string .= '<li>'. get_the_title(). '</li>';
                            
                            }
                        $string .= '</ul>';
                      
                        echo $string;

                     } ?>

               
            </div>

        </div>    
         <div class="wrap">
            <h1>Debate Settings</h1>
            <form action="options.php" method="POST" >
            <?php
            settings_fields("dbtplugin");
            do_settings_sections("dbt-settings-page");
            //submit_button();
           ?>
            </form>
           
        </div>
        <?php }
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

        register_post_type( 'debates', $args );
        //flush_rewrite_rules();
        
    }

    
    

}



 $debateProject = new DebateProject();