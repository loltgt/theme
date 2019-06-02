<?php
/**
 * Template Name: Archive
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


__( 'Archive', 'theme' );

get_header();
?>
<?php
if ( has_page_hero() ) :
	get_template_part( 'template-parts/hero' );
endif;
?>
<main id="content" class="site-content" role="main">
<?php
if ( is_active_sidebar( 'page-top' ) ) :
	get_template_part( 'template-parts/widgets', 'page-top' );
endif;
?>
<section class="archive container">
<?php if ( have_posts() && ! is_home() ) : ?>
<header class="page-header">
<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
<?php get_search_form(); ?>
</header>
<?php
if ( is_author() ) :
	get_template_part( 'template-parts/author' );
endif;
?>
<div class="card-columns">
<?php
while ( have_posts() ) : the_post();
	get_template_part( 'template-parts/entry', get_post_format() );
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