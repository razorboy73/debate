<?php
/**
 *
 * Oxford Debates Wordpress
 * File: Settings
 *
 **/

if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!class_exists("DBT_Settings")) :

    class DBT_Settings{

        function __construct()
        {
            add_action( 'add_meta_boxes', array($this,'dbt_meta_box') );
            add_action('save_post', array($this,'dbt_meta_save'));
            add_action( 'save_post', array($this,'dbt_posture_meta_save') );
            add_action('admin_notices', array($this,'invalid_positions'),0);
           
        }

        function invalid_positions(){
            //print the message
            global $post;
            $notice = get_option('invalid_postures');
            if (empty($notice)) return '';
            foreach($notice as $pid => $m){
                if ($post->ID == $pid ){
                    echo '<div id="message" class="error"><p>'.$m.'</p></div>';

                    unset($notice[$pid]);
                    update_option('invalid_positions',$notice);
                    break;
                }
            }
        }
        function dbt_meta_box(){
            add_meta_box(
            "moderator_box", 
            "Debate Details",
            [$this, "dbt_display_meta_box"],
            "debate",
            "advanced",
            "default"
            );
        }

        function dbt_display_meta_box($post){
            wp_nonce_field( basename( __FILE__ ), 'dbt_nonce' );
            $prfx_stored_meta = get_post_meta($post->ID );
            
             //insert a table 
            ?>
           
            

            <table >
                <tbody>
                    <tr>
                    </tr>
                </tbody>
            </table>
        <?php
        //detect the current post
        global $post;
        //get post metafields
        $custom = get_post_custom($post->ID);
        //User query 
        //user query arguments - 
        $user_args = array(
            'role'=>'Author',
            //order by display name
            'orderby' => 'display_name'
        );
        //run a user query for authors
        $wp_user_query = new WP_User_Query($user_args);

        $authors = $wp_user_query->get_results();

        if (!empty($authors))
			{

        ?>

			<tr valign="top">
				<th scope="row">
					<label for="positiona"><?php _e( 'Position A:', 'dbt' )?></label>
				</th>
				<td>
                    <!--choose position with drop down select -->
                    <select name="positiona">
                    <option value="no-posture-selected">Select a Position</option>
                    <?php
                    //loop through each position
                    //build arguments for custom query
                    $type = "position";
                    $args = array(
                        "post_type" =>$type,
                        "post_status"=>"publish",
                        "posts_per_page"=>-1,
                        'ignore_sticky_posts'=> 1
                    );
                    $my_query = null;
                    $my_query = new WP_Query($args);
                    if( $my_query->have_posts() ) {
                    while ($my_query->have_posts()) : $my_query->the_post();
                            $title = get_the_title();
                            ?>

                        <?php if (( isset ( $prfx_stored_meta['positiona'] ) ) and ( $prfx_stored_meta['positiona'][0] == $title )) { ?>
                        <option value="<?php the_title(); ?>" selected="selected"><?php the_title(); ?></option>
                        <?php
                        }
                        else
                        { ?>
                        <option value="<?php the_title(); ?>"><?php the_title(); ?></option>
                        <?php
                        }
                    endwhile;
                    
                    }
                    wp_reset_query();
                    echo "</select>";
                    ?>
                    <!-end of selecting position-->

                </td>
			</tr>
			<tr>
			</tr>
            <tr valign="top">
				<th scope="row">
					<label for="positionb">Position B</label>
				</th>
				<td>
                    <!--choose position with drop down select -->
                    <select name="positionb">
                    <option value="no-posture-selected">Select a Position</option>
                    <?php
                    //loop through each position
                    //build arguments for custom query
                    $args = array(
                        "post_type" =>"position",
                        // "position"=>"publish",
                        // "postions_per_page"=>-1,
                        // 'ignore_sticky_posts'=> 1
                    );
                    $my_query = null;
                    $my_query = new WP_Query($args);
                    if( $my_query->have_posts() ) {
                    while ($my_query->have_posts()) : $my_query->the_post();
                            $title = get_the_title();
                            ?>

                        <?php if (( isset ( $prfx_stored_meta['positionb'] ) ) and ( $prfx_stored_meta['positionb'][0] == $title )) { ?>
                        <option value="<?php the_title(); ?>" selected="selected"><?php the_title(); ?></option>
                        <?php
                        }
                        else
                        { ?>
                        <option value="<?php the_title(); ?>"><?php the_title(); ?></option>
                        <?php
                        }
                    endwhile;
                    
                    }
                    wp_reset_query();
                    echo "</select>";
                    ?>
                    <!-end of selecting position-->

                </td>
			</tr>
            <?php
            }else{
                echo "No authors found";
            }
            ?>
			</tbody>
			</table>
        <?php }


        function dbt_meta_save($post_id){
            $positiona = isset($_POST[ 'positiona' ]) ? $_POST[ 'positiona' ] : '';
            $positionb = isset($_POST[ 'positionb' ]) ? $_POST[ 'positionb' ] : '';
            $error = false;
		    // Checks save status
		    $is_autosave = wp_is_post_autosave( $post_id );
		    $is_revision = wp_is_post_revision( $post_id );
		    $is_valid_nonce = ( isset( $_POST[ 'dbt_nonce' ] ) && wp_verify_nonce( $_POST[ 'dbt_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
            
            // Exits script depending on save status
		    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		        return;
		    }
            // Checks for input and sanitizes/saves if needed
            if ((sanitize_text_field( $positionb ) == 'no-posture-selected') or (sanitize_text_field( $positiona ) == 'no-posture-selected')) { 
                // INVALID DATE
                $notice = get_option('invalid_position');
                $notice[$post_id] ='You must assign both positions before publishing. Now, your debate status is "Draft".';
                if (get_post_status($post_id) == 'publish') {
                    
                    $post = array( 'ID' => $post_id, 'post_status' => 'draft' );
                    wp_update_post($post);
                    
                }
                update_option('invalid_positions',$notice);
                
                return;
            } 
            // Checks for input and sanitizes/saves if needed
            update_post_meta( $post_id, "positiona", $positiona);
            update_post_meta( $post_id, "positionb", $positionb);
           
        }

        function dbt_posture_meta_save( $post_id ) {

		    // Checks save status
		    $is_autosave = wp_is_post_autosave( $post_id );
		    $is_revision = wp_is_post_revision( $post_id );
		    $is_valid_nonce = ( isset( $_POST[ 'dbt_nonce' ] ) && wp_verify_nonce( $_POST[ 'dbt_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
		 
		    // Exits script depending on save status
		    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		        return;
		    }

		}
    }
endif;
?>