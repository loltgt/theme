<?php
/**
 * Post template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer;


if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}

global $post;
?>

<article id="post-<?php the_data_ID(); ?>" <?php post_class( 'container' ); ?>>
<?php
/**
 * theme_post_start hook.
 *
 * @param int $post->ID - WP_Post
 */
do_action( 'theme_post_start', $post->ID );
?>
<header class="post-header">
<?php
/**
 * theme_post_header_start hook.
 *
 * @param int $post->ID - WP_Post
 */
do_action( 'theme_post_header_start', $post->ID );
?>
<?php
if ( is_single() ) :
	the_title( '<h1 class="post-title">', '</h1>' );
elseif ( is_front_page() && is_home() ) :
	the_title( '<h3 class="post-title"><a href="' . esc_attr( esc_url( get_permalink() ) ) . '" rel="bookmark">', '</a></h3>' );
else :
	the_title( '<h2 class="post-title"><a href="' . esc_attr( esc_url( get_permalink() ) ) . '" rel="bookmark">', '</a></h2>' );
endif;
?>
<?php if ( is_single() && 'post' == get_post_type() ) : ?>
<?php the_post_navigation(); ?>
<div class="post-meta">
<?php the_post_meta(); ?>
</div>
<?php endif; ?>
<?php
/**
 * theme_post_header_end hook.
 *
 * @param int $post->ID - WP_Post
 */
do_action( 'theme_post_header_end', $post->ID );
?>
</header>
<div class="post-content">
<?php
/**
 * theme_post_content_start hook.
 *
 * @param int $post->ID - WP_Post
 */
do_action( 'theme_post_content_start', $post->ID );
?>
<?php
the_content( sprintf(
	__( 'Continue reading<span class="sr-only sr-only-focusable"> "%s"</span>', 'theme' ),
	get_the_title()
) );
?>
<?php
/**
 * theme_post_content_end hook.
 *
 * @param int $post->ID - WP_Post
 */
do_action( 'theme_post_content_end', $post->ID );
?>
</div>
<?php if ( is_single() ) : ?>
<footer class="post-footer">
<?php
/**
 * theme_post_footer_start hook.
 *
 * @param int $post->ID - WP_Post
 */
do_action( 'theme_post_footer_start', $post->ID );
?>
<?php
the_post_footer();

if ( Layer::get_field( 'enable_footer_notes' ) ) :
	echo apply_filters( 'the_content', get_field( 'footer_notes' ) );
endif;

wp_link_pages();
?>
<?php
/**
 * theme_post_footer_end hook.
 *
 * @param int $post->ID - WP_Post
 */
do_action( 'theme_post_footer_end', $post->ID );
?>
</footer>
<?php endif; ?>
<?php
/**
 * theme_post_end hook.
 *
 * @param int $post->ID - WP_Post
 */
do_action( 'theme_post_end', $post->ID );
?>
</article>
