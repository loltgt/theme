<?php
/**
 * Navigation (primary) template part
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


?>
<nav id="navigation-primary" class="site-navigation site-navigation-primary navbar navbar-expand-md" aria-label="<?php echo esc_attr_x( 'Menu', 'navigation-primary', 'theme' ); ?>">
<?php the_custom_logo(); ?>
<?php
if ( has_nav_menu( 'primary' ) ) :
	wp_nav_menu( array(
		'theme_location' => 'primary',
		'menu_id' => 'menu-primary',
		'menu_class' => 'navbar-nav',
		'container' => 'div',
		'container_class' => 'container',
		'depth' => 2
	) );
endif;
?>
</nav>
