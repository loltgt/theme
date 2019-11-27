<?php
/**
 * Default template part for empty content
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


$is_search = is_search();
?>

<div class="no-results not-found container">
<?php if ( ! $is_search ) : ?>
<header class="page-header">
	<h1 class="page-title"><?php _e( 'Nothing Found', 'theme' ); ?></h1>
</header>
<?php endif; ?>
<div class="page-content">
<?php
if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
	<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'heme' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
<?php else : ?>
<?php if ( $is_search ) : ?>
	<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'theme' ); ?></p>
<?php else : ?>
	<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help &hellip;', 'theme' ); ?></p>
<?php endif; ?>
	<?php get_search_form(); ?>
<?php endif; ?>
</div>
</div>
