<?php
/**
 * Oxd displaying Comments
 *
 *
 */

if ( post_password_required() )
	return;

$current_posturea = html_entity_decode(get_post_meta( get_the_ID(), 'posturea', true ));
$current_postureb = html_entity_decode(get_post_meta( get_the_ID(), 'postureb', true ));
$fluid = get_option('oxd_fluid');

?>

<div id="comments" class="container<?php if ($fluid == 'yes') { echo '-fluid'; } ?> debates-container">
    <div class="row">
    
	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<p id="comments-title">
			<?php _e('Comments','oxd'); echo ' (' . number_format_i18n( get_comments_number() ) .')'; ?>
		</p>
        <div id="comments-list">
		<ol class="commentlist">
			<?php
				$arrcommA = array(
				'post_id' => get_the_ID()
				);
				$comments = get_comments($arrcommA);
			?>
			
			
			<?php wp_list_comments(); ?>

		
		</ol><!-- .commentlist -->
        </div><!-- #comments .comments-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="navigation" role="navigation">
			<h1 class="assistive-text section-heading"><?php _e( 'Comment navigation', 'oxd' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( _e( '&larr; Older Comments', 'oxd' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( _e( 'Newer Comments &rarr;', 'oxd' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<?php
		/* If there are no comments and comments are closed, let's leave a note.
		 * But we only want the note on posts and pages that had comments in the first place.
		 */
		if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.' , 'oxd' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php $comment_args = array( 
    'title_reply'=>__('Got Something To Say','oxd'),
    'fields' => apply_filters( 'comment_form_default_fields', array(
		'author' => '<div>' .
				'<div>' .
					'<span id="basic-name">' . __('Your name','oxd') .'*</span>' .
					'<input id="author" name="author" type="text" class="form-control" aria-describedby="basic-name" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30">' .
				'</div>' .
			'</div>', 
		'email'  => '<div>' .
				'<div>' .
					'<span id="basic-email">' . __('Your email','oxd') .'*</span>' .
					'<input id="email" name="email" type="text" class="form-control" aria-describedby="basic-email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" >' .
				'</div>' .
			'</div>',
		'url'    => '' )
	),
    'comment_field' => 
		'<p>' .
    	'<span class="required">*</span>' .
    	'<select name="posture" class="form-control" aria-describedby="basic-posture" id="posture-selector">' .
					'<option value="' . $current_posturea . '">' . __('Proposal','oxd') .' A</option>' .
					'<option value="' . $current_postureb . '">' . __('Proposal','oxd') .' B</option>' .
					'<option value="' . __('Other','oxd') .'">' . __('Other','oxd') .'</option>' .
				'</select>' .
		'<p>' .
        '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>' .
        '</p>',
    'comment_notes_after' => '',
);

comment_form($comment_args); 
?>
    </div>
</div><!-- #comments .comments-area -->
