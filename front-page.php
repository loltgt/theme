<?php
/**
 * Front page template
 *
 * //TODO review
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


get_header();
?>
<div id="content" class="site-content">
<?php get_template_part( 'template-parts/hero' ); ?>
<main id="main" class="site-main" role="main">
<?php
if ( is_active_sidebar( 'front-page-top' ) ) :
	get_template_part( 'template-parts/widgets', 'front-page-top' );
endif;
?>
<?php
while ( have_posts() ) : the_post();

	get_template_part( 'template-parts/page', \theme\get_page_template_name() );

	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;

endwhile;
?>
<?php
if ( is_active_sidebar( 'front-page-bottom' ) ) :
	get_template_part( 'template-parts/widgets', 'front-page-bottom' );
endif;
?>
</main>
</div>

<?php
get_footer();