<?php
/**
 * Navigation (secondary) template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \WP_Bootstrap_Navwalker;


?>
<nav id="navigation-secondary" class="site-navigation site-navigation-secondary navbar" role="navigation" aria-label="<?php echo esc_attr_x( 'Menu', 'menu-secondary', 'theme' ); ?>">
<?php
if ( is_active_sidebar( 'navigation-secondary-before' ) ) :
	get_template_part( 'template-parts/widgets', 'navigation-secondary-before' );
endif;
?>
<?php
wp_nav_menu( array(
	'theme_location' => 'secondary',
	'menu_id' => 'menu-secondary',
	'menu_class' => 'navbar-nav',
    'container' => 'div',
    'container_class' => 'container',
	'depth' => 2,
	'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
	'walker' => new WP_Bootstrap_Navwalker()
) );
?>
<?php
if ( is_active_sidebar( 'navigation-secondary-after' ) ) :
	get_template_part( 'template-parts/widgets', 'navigation-secondary-after' );
endif;
?>
</nav>
