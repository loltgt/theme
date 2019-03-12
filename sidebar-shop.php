<?php
/**
 * Sidebar (shop)
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


if ( ! is_active_sidebar( 'shop' ) )
	return;
?>
<aside id="sidebar-shop" class="widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Shop', 'theme' ); ?>">
<?php dynamic_sidebar( 'shop' ); ?>
</aside>
