<?php
/**
 * Widgets (after navigation secondary) template part
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


if ( ! is_active_sidebar( 'navigation-secondary-after' ) )
	return;
?>
<aside id="widgets-after-menu-secondary" class="widget-area" aria-label="<?php echo esc_attr_x( 'Menu', 'navigation-secondary-before', 'theme' ); ?>">
<div class="widget-column widgets-after-menu-secondary">
<?php dynamic_sidebar( 'navigation-secondary-after' ); ?>
</div>
</aside>
