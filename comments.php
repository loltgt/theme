<?php
/**
 * Comments template
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \Bootstrap_Comment_Walker;


if ( post_password_required() )
	return;
?>
<section id="comments" class="comments-area">
<?php
if ( have_comments() ) : ?>
<h2 class="comments-title">
<?php
$comments_number = get_comments_number();
if ( '1' === $comments_number ) {
	_ex( 'Comments', 'comments title', 'theme' );
} else {
	printf(
		_x(
			'Comments %d',
			$comments_number,
			'comments title',
			'theme'
		),
		number_format_i18n( $comments_number )
	);
}
?>
</h2>

<ul class="list-unstyled comment-list">
<?php
wp_list_comments( array(
	'avatar_size' => 64,
	'style' => 'ul',
	'short_ping' => true,
	'reply_text' => __( 'Reply' ),
	'walker' => new Bootstrap_Comment_Walker()
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
<?php comment_form(); ?>
</section>
