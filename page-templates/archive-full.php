<?php
/**
 * Template Name: Archive (full posts)
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


__( 'Archive (full posts)', 'theme' );

global $wp_query;

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
<section class="archive container">
<?php if ( have_posts() && ! is_home() ) : ?>
<header class="page-header">
<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
</header>
<?php
if ( is_author() ) :
	get_template_part( 'template-parts/author' );
endif;
?>
<div class="posts">
<?php
if ( isset( $wp_query->query ) ) {
	$args = array_merge( $wp_query->query, array(
		'post_type' => 'post'
	) );

	unset( $args['page'] );
	unset( $args['pagename'] );
} else {
	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish'
	);
}

query_posts( $args );

while ( have_posts() ) : the_post();
	get_template_part( 'template-parts/post', get_post_format() );
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
</div>

<?php
get_footer();