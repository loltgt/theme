<?php
/**
 * Widget_Recent_Posts template
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


$args = get_query_var( 'widget_args' );

if ( ! $args || ! $args['posts'] )
	return;
?>

<?php echo $args['before_widget']; ?>
<?php
if ( $args['title'] ) :
	echo $args['before_title'] . $args['title'] . $args['after_title'];
endif;
?>
<ul class="list-unstyled recent-posts">
<?php
foreach ( $args['posts']->posts as $post ) :
	setup_postdata( $post );

	$post_title = get_the_title();
	$title = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
?>
	<li>
		<div class="media recent-post">
			<?php the_entry_post_thumbnail( null, 'post-thumbnail', array('class' => 'img-fluid') ); ?>
			<div class="media-body ml-3">
				<h5 class="entry-title mt-0 mb-3"><a href="<?php the_permalink(); ?>"><?php echo $title ; ?></a></h5>
				<?php the_entry_post_excerpt(); ?>
				<?php if ( $args['show_date'] ) : ?>
					<div class="recent-post--meta entry-meta mt-2">
						<?php the_entry_post_meta(); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</li>
<?php endforeach; ?>
</ul>
<?php
wp_reset_postdata();

echo $args['after_widget'];