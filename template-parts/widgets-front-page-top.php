<?php
/**
 * Widgets (front page top) template part
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


if ( ! is_active_sidebar( 'front-page-top' ) )
	return;
?>
<aside id="widgets-top-front-page" class="widget-area" aria-label="<?php echo esc_attr_x( 'Page', 'front-page-top', 'theme' ); ?>">
<div class="widget-column widgets-top-front-page container">
<?php dynamic_sidebar( 'front-page-top' ); ?>
</div>
</aside>
