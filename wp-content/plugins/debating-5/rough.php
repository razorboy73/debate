

class DBTPlugin{

function __construct()
{
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
        'rewrite' => array('slug' => 'posture'),
        'show_in_rest'          => true,

    );


    register_taxonomy("position_category", "position", $args);

}

function save_comment_meta_data($comment_id){
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