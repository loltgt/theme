<?php
/**
 * Footer template (default)
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


$has_nav_menu = has_nav_menu( 'footer' );
$has_widgets_before_menu = is_active_sidebar( 'footer-before' );
$has_widgets_after_menu = is_active_sidebar( 'footer-after' );
?>
</div>
<?php if ( $has_nav_menu || $has_widgets_before_menu || $has_widgets_after_menu ) : ?>

<footer id="footer" class="site-footer" role="contentinfo">
<div class="container">
<?php
if ( is_active_sidebar( 'footer-before' ) ) :
	get_template_part( 'template-parts/widgets', 'footer-before' );
endif;
?>
<?php
if ( has_nav_menu( 'footer' ) ) :
	get_template_part( 'template-parts/navigation', 'footer' );
endif;
?>
<?php
if ( is_active_sidebar( 'footer-after' ) ) :
	get_template_part( 'template-parts/widgets', 'footer-after' );
endif;
?>
</div>
</footer>

<?php endif; ?>
</div>
<?php wp_footer(); ?>
</body>
</html>