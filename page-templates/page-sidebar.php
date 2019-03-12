<?php
/**
 * Template Name: Page (sidebar)
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


__( 'Page (sidebar)', 'theme' );

$page_dispose = get_theme_mod( 'page_dispose', 'ltr' );

get_header();
?>
<div id="content" class="site-content">
<?php get_template_part( 'template-parts/hero' ); ?>
<main id="main" class="site-main" role="main">
<?php
if ( is_active_sidebar( 'page-top' ) ) :
	get_template_part( 'template-parts/widgets', 'page-top' );
endif;
?>
<div class="container">
<div class="row">
<?php
if ( $page_dispose === 'rtl' ) :
	get_sidebar();
endif;
?>
<div class="col-md-8">
<?php
while ( have_posts() ) : the_post();

	get_template_part( 'template-parts/page', \theme\get_page_template_name() );

	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;

endwhile;
?>
</div>
<?php
if ( $page_dispose === 'ltr' ) :
	get_sidebar();
endif;
?>
</div>
</div>
<?php
if ( is_active_sidebar( 'page-bottom' ) ) :
	get_template_part( 'template-parts/widgets', 'page-bottom' );
endif;
?>
</main>
</div>

<?php
get_footer();