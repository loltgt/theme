<?php
/**
 * Widgets (single post top) template part
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


if ( ! is_active_sidebar( 'post-top' ) )
	return;
?>
<aside id="widgets-top-post" class="widget-area" aria-label="<?php echo esc_attr_x( 'Post', 'post-top', 'theme' ); ?>">
<div class="widget-column widgets-top-post container">
<?php dynamic_sidebar( 'post-top' ); ?>
</div>
</aside>
