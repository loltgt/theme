<?php
/**
 * Widgets (single post bottom) template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


if ( ! is_active_sidebar( 'post-bottom' ) )
	return;
?>
<aside id="widgets-bottom-post" class="widget-area" role="complementary" aria-label="<?php echo esc_attr_x( 'Post', 'post-bottom', 'theme' ); ?>">
<div class="widget-column widgets-bottom-post container">
<?php dynamic_sidebar( 'post-bottom' ); ?>
</div>
</aside>
