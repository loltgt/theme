<?php
/**
 * Widgets (before footer) template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


if ( ! is_active_sidebar( 'footer-before' ) )
	return;
?>
<aside id="widgets-before-footer" class="widget-area" role="complementary" aria-label="<?php echo esc_attr_x( 'Footer', 'footer-before', 'theme' ); ?>">
<div class="widget-column widgets-before-footer">
<?php dynamic_sidebar( 'footer-before' ); ?>
</div>
</aside>
