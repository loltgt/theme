<?php
/**
 * Widgets (single page bottom) template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


if ( ! is_active_sidebar( 'page-bottom' ) )
	return;
?>
<aside id="widgets-bottom-page" class="widget-area" role="complementary" aria-label="<?php echo esc_attr_x( 'Page', 'page-bottom', 'theme' ); ?>">
<div class="widget-column widgets-bottom-page container">
<?php dynamic_sidebar( 'page-bottom' ); ?>
</div>
</aside>
