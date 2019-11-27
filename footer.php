<?php
/**
 * Footer template
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


$has_nav_menu = has_nav_menu( 'footer' );
$has_widgets_before_menu = is_active_sidebar( 'footer-before' );
$has_widgets_after_menu = is_active_sidebar( 'footer-after' );
?>
</div>
<?php if ( $has_nav_menu || $has_widgets_before_menu || $has_widgets_after_menu ) : ?>

<footer id="footer" class="site-footer">
<div class="container">
<?php
if ( $has_widgets_before_menu ) :
	get_template_part( 'template-parts/widgets', 'footer-before' );
endif;
?>
<?php
if ( $has_nav_menu ) :
	get_template_part( 'template-parts/navigation', 'footer' );
endif;
?>
<?php
if ( $has_widgets_after_menu ) :
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