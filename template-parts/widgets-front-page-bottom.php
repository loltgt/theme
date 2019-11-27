<?php
/**
 * Widgets (front page bottom) template part
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


if ( ! is_active_sidebar( 'front-page-bottom' ) )
	return;
?>
<aside id="widgets-bottom-front-page" class="widget-area" aria-label="<?php echo esc_attr_x( 'Page', 'front-page-bottom', 'theme' ); ?>">
<div class="widget-column widgets-bottom-front-page container">
<?php dynamic_sidebar( 'front-page-bottom' ); ?>
</div>
</aside>
