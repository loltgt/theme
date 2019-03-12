<?php
/**
 * Sidebar template (default)
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


if ( ! is_active_sidebar( 'sidebar' ) )
	return;
?>
<aside id="sidebar" class="col-md-4 widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'theme' ); ?>">
<?php dynamic_sidebar( 'sidebar' ); ?>
</aside>
