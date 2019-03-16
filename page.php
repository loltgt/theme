<?php
/**
 * Single page template
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


get_header();
?>
<?php
if ( has_page_hero() ) :
	get_template_part( 'template-parts/hero' );
endif;
?>
<main id="main" class="site-main" role="main">
<?php
if ( is_active_sidebar( 'page-top' ) ) :
	get_template_part( 'template-parts/widgets', 'page-top' );
endif;
?>
<?php
while ( have_posts() ) : the_post();

	get_template_part( 'template-parts/page', \theme\get_page_template_name() );

	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;

	wp_link_pages( array(
		'before' => '<div class="page-links">' . __( 'Pages:', 'theme' ),
		'after'  => '</div>',
	) );

endwhile;
?>
<?php
if ( is_active_sidebar( 'page-bottom' ) ) :
	get_template_part( 'template-parts/widgets', 'page-bottom' );
endif;
?>
</main>

<?php
get_footer();