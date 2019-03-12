<?php
/**
 * Widgets (before navigation secondary) template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


if ( ! is_active_sidebar( 'navigation-secondary-before' ) )
	return;
?>
<aside class="widget-area" role="complementary" aria-label="<?php echo esc_attr_x( 'Menu', 'navigation-secondary-before', 'theme' ); ?>">
<div class="widget-column widgets-navigation-secondary-before">
<?php dynamic_sidebar( 'navigation-secondary-before' ); ?>
</div>
</aside>
