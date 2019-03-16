<?php
/**
 * Navigation (primary) template part
 *
 * //TODO collapsable
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \WP_Bootstrap_Navwalker;


?>
<nav id="navigation-primary" class="site-navigation site-navigation-primary navbar navbar-expand-md" role="navigation" aria-label="<?php echo esc_attr_x( 'Menu', 'navigation-primary', 'theme' ); ?>">
<?php the_custom_logo(); ?>
<?php
if ( has_nav_menu( 'primary' ) ) :
	wp_nav_menu( array(
		'theme_location' => 'primary',
		'menu_id' => 'menu-primary',
		'menu_class' => 'navbar-nav',
		'container' => 'div',
		'container_class' => 'container',/*'collapse navbar-collapse',*/
		'depth' => 2,
		'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
		'walker' => new WP_Bootstrap_Navwalker()
	) );
endif;
?>
</nav>
