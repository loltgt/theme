<?php
/**
 * Widgets (after footer) template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


if ( ! is_active_sidebar( 'footer-after' ) )
	return;
?>
<aside id="widgets-after-footer" class="widget-area" role="complementary" aria-label="<?php echo esc_attr_x( 'Footer', 'footer-after', 'theme' ); ?>">
<div class="widget-column widgets-after-footer">
<?php dynamic_sidebar( 'footer-after' ); ?>
</div>
</aside>
