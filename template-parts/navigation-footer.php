<?php
/**
 * Navigation (footer) template part
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


?>
<nav id="navigation-footer" class="site-navigation site-navigation-footer" aria-label="<?php echo esc_attr_x( 'Menu', 'navigation-footer', 'theme' ); ?>">
<?php
wp_nav_menu( array(
	'theme_location' => 'footer',
	'menu_id' => 'menu-footer',
	'menu_class' => 'nav',
	'depth' => 2
) );
?>
</nav>
