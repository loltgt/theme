<?php
/**
 * Search page template
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


get_header();
?>
<main id="content" class="site-content" role="main">
<?php
if ( is_active_sidebar( 'page-top' ) ) :
	get_template_part( 'template-parts/widgets', 'page-top' );
endif;
?>
<section class="search container">
<header class="page-header">
<?php if ( have_posts() ) : ?>
	<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'theme' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
<?php else : ?>
	<h1 class="page-title"><?php _e( 'Nothing Found', 'theme' ); ?></h1>
<?php endif; ?>
</header>
<?php if ( have_posts() ) : ?>
<div>
<?php
while ( have_posts() ) : the_post();
	get_template_part( 'template-parts/search-entry', get_post_format() );
endwhile;
?>
</div>
<?php the_posts_pagination(); ?>
<?php
else :
	get_template_part( 'template-parts/none' );
endif;
?>
</section>
<?php
if ( is_active_sidebar( 'page-bottom' ) ) :
	get_template_part( 'template-parts/widgets', 'page-bottom' );
endif;
?>
</main>

<?php
get_footer();