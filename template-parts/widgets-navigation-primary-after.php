<?php
/**
 * Widgets (after navigation primary) template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


if ( ! is_active_sidebar( 'navigation-primary-after' ) )
	return;
?>
<aside id="widgets-after-menu-primary" class="widget-area" role="complementary" aria-label="<?php echo esc_attr_x( 'Menu', 'navigation-primary-before', 'theme' ); ?>">
<div class="widget-column widgets-after-menu-primary">
<?php dynamic_sidebar( 'navigation-primary-after' ); ?>
</div>
</aside>
