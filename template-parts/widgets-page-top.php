<?php
/**
 * Widgets (single page top) template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


if ( ! is_active_sidebar( 'page-top' ) )
	return;
?>
<aside id="widgets-top-page" class="widget-area" role="complementary" aria-label="<?php echo esc_attr_x( 'Page', 'page-top', 'theme' ); ?>">
<div class="widget-column widgets-top-page container">
<?php dynamic_sidebar( 'page-top' ); ?>
</div>
</aside>
