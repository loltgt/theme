<?php
/**
 * Comments template
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


if ( post_password_required() )
	return;
?>
<section id="comments" class="comments-area">
<div class="comments-list">
<?php
if ( have_comments() ) : ?>
<h2 class="comments-title"><?php _e( 'Comments' ); ?></h2>

<ul class="list-unstyled comment-list">
<?php
wp_list_comments( array(
	'avatar_size' => 64,
	'style' => 'ul',
	'short_ping' => true,
	'reply_text' => __( 'Reply' )
) );
?>
</ul>
<?php the_comments_pagination(); ?>
<?php endif; ?>
<?php
if ( comments_open() ) :
	wp_enqueue_script( 'comment-reply' );
elseif ( get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
?>
	<p class="no-comments"><?php _e( 'Comments are closed.', 'theme' ); ?></p>
<?php endif; ?>
</div>
<div class="comments-form">
<?php comment_form(); ?>
</div>
</section>
