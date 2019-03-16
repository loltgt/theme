<?php
/**
 * Template Name: Nest
 *
 *
 * @global null|object $post - \WP_Post
 * @global null|int $page_id
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


__( 'Nest', 'theme' );

global $post, $page_id;

//TODO check $GLOBALS $post postdata reset
$page_id = empty( $page_id ) ? $post->ID : $page_id;

get_header();
?>
<?php
if ( has_page_hero() ) :
	get_template_part( 'template-parts/hero' );
endif;
?>
<main id="content" class="site-content" role="main">
<?php
while ( have_posts() ) : the_post();

	get_template_part( 'template-parts/page', \theme\get_page_template_name() );

	if ( $page_id ) :
		$children = get_children( array(
			'post_parent' => $page_id,
			'post_type' => 'page',
			'numberposts' => -1,
			'post_status' => 'publish'
		) );

		foreach ( $children as $post ) : setup_postdata( $post );
			get_template_part( 'template-parts/child-page', \theme\get_page_template_name() );
		endforeach;

		wp_reset_postdata();
	endif;

	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;

endwhile;
?>
</main>

<?php
get_footer();