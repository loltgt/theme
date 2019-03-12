<?php
/**
 * Navigation (footer) template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \WP_Bootstrap_Navwalker;


?>
<nav id="navigation-footer" class="site-navigation site-navigation-footer" role="navigation" aria-label="<?php echo esc_attr_x( 'Menu', 'navigation-footer', 'theme' ); ?>">
<?php
wp_nav_menu( array(
	'theme_location' => 'footer',
	'menu_id' => 'menu-footer',
	'menu_class' => 'nav',
	'depth' => 2,
	'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
	'walker' => new WP_Bootstrap_Navwalker()
) );
?>
</nav>
