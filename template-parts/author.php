<?php
/**
 * Author template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


?>
<section class="author-info">
<h2 class="author-heading"><?php _e( 'Published by', 'theme' ); ?></h2>
<div class="author-avatar">
<?php echo get_avatar( get_the_author_meta( 'user_email' ), 128 ); ?>
</div>

<div class="author-description">
	<h3 class="author-title"><?php the_author(); ?></h3>
	<p class="author-bio">
		<?php the_author_meta( 'description' ); ?>
<?php if ( ! is_author() ) : ?>
		<a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
			<?php printf( __( 'View all posts by %s', 'theme' ), get_the_author() ); ?>
		</a>
<?php endif; ?>
	</p>
</div>
</section>
