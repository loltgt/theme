<?php
/**
 * Template Name: Page (sidebar)
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


__( 'Page (sidebar)', 'theme' );

$is_rtl = is_rtl();

get_header();
?>
<main id="content" class="site-content">
<?php
if ( is_active_sidebar( 'page-top' ) ) :
	get_template_part( 'template-parts/widgets', 'page-top' );
endif;
?>
<div class="container">
<div class="row">
<?php
if ( $is_rtl ) :
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
if ( ! $is_rtl ) :
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

<?php
get_footer();