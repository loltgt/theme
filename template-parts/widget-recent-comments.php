<?php
/**
 * Widget_Recent_Comments template
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


$args = get_query_var( 'widget_args' ):

if ( ! $args || ! $args['comments'] )
	return;
?>

<?php echo $args['before_widget']; ?>
<?php
if ( $args['title'] ) :
	echo $args['before_title'] . $args['title'] . $args['after_title'];
endif;
?>
<ul id="recentcomments">
<?php foreach ( (array) $args['comments'] as $comment ) : ?>
	<li class="recentcomments">
<?php
		printf(
			_x( '%1$s on %2$s', 'widgets' ),
			"\t\t<span class=\"comment-author-link\">" . get_comment_author_link( $comment ) . "</span>",
			"\t\t<a href=\"" . esc_url( get_comment_link( $comment ) ) . "\">" .
				get_the_title( $comment->comment_post_ID ) . "</a>"
		);
?>
	</li>
<?php endforeach; ?>
</ul>
<?php
echo $args['after_widget'];
