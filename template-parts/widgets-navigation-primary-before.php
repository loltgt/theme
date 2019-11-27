<?php
/**
 * Widgets (before navigation primary) template part
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


if ( ! is_active_sidebar( 'navigation-primary-before' ) )
	return;
?>
<aside id="widgets-before-menu-primary" class="widget-area" aria-label="<?php echo esc_attr_x( 'Menu', 'navigation-primary-before', 'theme' ); ?>">
<div class="widget-column widgets-before-menu-primary">
<?php dynamic_sidebar( 'navigation-primary-before' ); ?>
</div>
</aside>
