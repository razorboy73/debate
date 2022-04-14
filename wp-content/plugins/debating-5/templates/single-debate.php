<?php 
global $url;
$url = home_url();
global $comments;
global $posture;


?>
<?php
/**
* Template Name: Debate Page
*
* 
*/
get_header();

//set up a custom query arguments
while (have_posts()){
	the_post();
	$post = get_post();
	$debate_id = $post->ID;
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
    // loop trough each posture
    $type = 'position';
    $args=array(
        'post_type' => $type,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'ignore_sticky_posts'=> 1
    );

	$my_query = null;
    $my_query = new WP_Query($args);
	//for each post, get its id and then find the post meta value for the position key
    $current_positiona = html_entity_decode(get_post_meta( get_the_ID(), 'positiona', true ));
    $current_positionb = html_entity_decode(get_post_meta( get_the_ID(), 'positionb', true ));


	if($my_query->have_posts()){
		while ($my_query->have_posts()){
			$my_query->the_post();
			$title = html_entity_decode(get_the_title());
			//match up the title of the postition with the indicated position for the debate
			if(html_entity_decode($current_positiona)==html_entity_decode($title) ) {
                $positiona = get_the_content('More');
                $authora = get_the_author_meta('ID');
            }
			if(html_entity_decode($current_positionb)==html_entity_decode($title) ) {
                $positionb = get_the_content('More');
                $authorb = get_the_author_meta('ID');
            }
		}

	}
	wp_reset_query();
	$usera = get_userdata($authora);
	$userb = get_userdata($authorb);
	?>
	<input type="hidden" name="debate-id" id="debate-id" value="<?php echo $debate_id; ?>"/>
	<input type="hidden" name="user-id" id="user-id" value="<?php echo $user_id; ?>"/>

	<div id="debate-section" class="container debates-container">
    	<div class="row">
        	<h1>
          		<?php echo $post->post_title; ?>
        	</h1>
			<p id="debate-tags">
          <?php the_tags( ' ', ', ', '<br />' ); ?>
        </p>
		</div>
	</div>

	<div class="row">
        <div id="response-container">
        </div>
        <p id="author_name">
          <?php the_author_meta( 'first_name' ); ?> 
          <?php the_author_meta( 'last_name' ); ?>
        </p>
        <p id="author_description">
          <?php the_author_meta( 'user_description' ); ?>
        </p>
        <hr class="debates-hr">
        <p>
          <?php echo $post->post_content;?>
        </p>
    </div> 
</div>
<div class="row">
    
      
	<div class="postures-container col col-sm-6" id="postures-container-a">
	  <div id="postures-title-container-a">
		<div id="postures-title-a" style="background-color: red">
		  <p>
			<span>
			  Position A
			</span>
			<?php echo get_post_meta( get_the_ID(), 'positiona', true );?>
		  </p>
		</div>
	  </div>
	  <div id="postures-content-a">
		<div class="speaker-info-div">
		  <?php 
		  echo get_avatar( $authora, 160 );
			?>
		  <p class="speaker-name">
			<?php echo $usera->first_name . ' ' . $usera->last_name; ?>
		  </p>
		  <p class="speaker-description">
			<?php echo $usera->description; ?>
		  </p> 
		</div>
		<div>
		  <p>
			<?php echo $position; ?>
		  </p>
		</div>  
	  </div>
	</div>
	  <div class="postures-container col col-sm-6" id="postures-container-b">
          <div id="postures-title-container-b">
            <div id="postures-title-b" style="background-color: blue">
              <p>
                <span>
                  Position B
                </span>
                <?php echo get_post_meta( get_the_ID(), 'positionb', true );?>
              </p>
            </div>
          </div>
          <div id="postures-content-b">
            <div class="speaker-info-div">
                <?php 
                echo get_avatar( $authorb, 160 );
                ?>
              <p class="speaker-name">
                <?php echo $userb->first_name . ' ' . $userb->last_name; ?>
              </p>
              <p class="speaker-description">
                <?php echo $userb->description; ?>
              </p> 
            </div>
            <div>
              <p>
                <?php echo $positionb; ?>
              </p>
            </div>  
          </div>
	</div>

	</div>
	<?php comments_template( $file = plugin_dir_path( __FILE__ ) . '/comments-debate.php', $separate_comments = false ); ?>

<?php } ?>




 <?php get_footer(); ?>