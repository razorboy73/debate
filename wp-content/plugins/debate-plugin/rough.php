//Add an administration page


add_action("admin_menu", "adminPage");
function adminPage(){
    add_menu_page("Debate Settings", "Debate Display",
    "manage_options","deb-settings-page", "debHTML");
}

function debHTML(){ ?>
    <div class="wrap">
        <h1>Debate Settings</h1>
        <form action="options.php" method="POST" >
        <?php
        //settings_fields("lmtplugin");
        do_settings_sections("deb-settings-page");
        submit_button();
        ?>
        </form>
    </div>
    <?php }

add_action("admin_init", "settings");








function settings(){
        
    add_settings_section("deb_first_section","Optional Heading (set to Null if not needed)","optional_content", "deb-settings-page" );
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
function optional_content(){
    echo "Optional Content - could be set to null.  If this is inside a class, remember to use (array(($)this,callback)) to use function";
}



//Modification of user registation Page
add_action( 'register_form', 'myplugin_register_form' );
function myplugin_register_form() {

    $first_name = ( ! empty( $_POST['first_name'] ) ) ? sanitize_text_field( $_POST['first_name'] ) : '';
        
        ?>
        <p>
            <label for="first_name"><?php _e( 'First Name', 'mydomain' ) ?><br />
                <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr(  $first_name  ); ?>" size="25" /></label>
        </p>
        <?php
    }

    //2. Add validation. In this case, we make sure first_name is required.
    add_filter( 'registration_errors', 'myplugin_registration_errors', 10, 3 );
    function myplugin_registration_errors( $errors, $sanitized_user_login, $user_email ) {
        
        if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
        $errors->add( 'first_name_error', sprintf('<strong>%s</strong>: %s',__( 'ERROR', 'mydomain' ),__( 'You must include a first name.', 'mydomain' ) ) );

        }

        return $errors;
    }

    //3. Finally, save our extra registration user meta.
    add_action( 'user_register', 'myplugin_user_register' );
    function myplugin_user_register( $user_id ) {
        if ( ! empty( $_POST['first_name'] ) ) {
            update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST['first_name'] ) );
        }
    }

// add_action( 'register_form', 'wporg_myplugin_add_registration_fields' );
 
// function wporg_myplugin_add_registration_fields() {
 
//     // Get and set any values already sent
//     $user_extra = ( isset( $_POST['user_extra'] ) ) ? $_POST['user_extra'] : '';
/*?>
 
//     <p>
//         <label for="user_extra"><?php _e( 'Extra Field', 'myplugin_textdomain' ) ?><br />
//         <input type="text" name="user_extra" id="user_extra" class="input" value="<?php echo esc_attr( stripslashes( $user_extra ) ); ?>" size="25" /></label>
//     </p>
*/
 
//     <?php
// }
