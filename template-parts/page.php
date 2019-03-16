<?php
/**
 * Page template part
 *
 *
 * @global null|object $post - \WP_Post
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer;


global $post;

$wide_mode = is_page_mode( 'wide' );
?>
<section id="page-<?php the_data_ID(); ?>" <?php post_class(); the_data_extras( 'page' ); ?>>
<?php
/**
 * theme_page_start hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_start', $post->ID );
?>
<?php
//TODO improve
if ( false === strpos( $post->post_content, '</h', ( strlen( $post->post_content >= 100 ? 100 : null ) ) ) ) :
?>
<header class="page-header">
<div class="container">
<?php
/**
 * theme_page_header_start hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_header_start', $post->ID );
?>
<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
<?php
/**
 * theme_page_header_end hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_header_end', $post->ID );
?>
</div>
</header>
<?php endif; ?>
<div class="page-content">
<?php if ( ! $wide_mode ) : ?>
<div class="container">
<?php endif; ?>
<?php
/**
 * theme_page_content_start hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_header_start', $post->ID );
?>
<?php the_content(); ?>
<?php if ( ! $wide_mode ) : ?>
</div>
<?php endif; ?>
</div>
<?php if ( Layer::have_rows( 'layers' ) ) : ?>
<div class="page-content">
<?php while ( Layer::have_rows( 'layers' ) ) : Layer::the_row(); ?>
<?php
/**
 * theme_page_layer_loop_start hook.
 *
 * @hooked \theme\Functions->page_layer_loop_start - 10
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_layer_loop_start', $post->ID );
?>
<div <?php the_data_class( 'page_layer', 'page-content-layer' ); the_data_extras( 'page_layer' ); ?>>
<?php
/**
 * theme_page_layer_content_start hook.
 *
 * @hooked \theme\Functions->page_layer_content_start - 10
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_layer_content_start', $post->ID );
?>
<?php if ( Layer::have_rows( 'block' ) ) : while ( Layer::have_rows( 'block' ) ) : Layer::the_row(); ?>
<?php
/**
 * theme_page_block_loop_start hook.
 *
 * @hooked \theme\Functions->start_page_block_loop - 10
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_block_loop_start', $post->ID );
?>
<div <?php the_data_class( 'page_block', 'page-content-block' ); ?>>
<?php get_template_part( 'template-parts/block', Layer::get_field_name() ); ?>
</div>
<?php
/**
 * theme_page_block_loop_end hook.
 *
 * @hooked \theme\Functions->end_page_block_loop - 10
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_block_loop_end', $post->ID );
?>
<?php endwhile; endif; ?>
<?php
/**
 * theme_page_layer_content_end hook.
 *
 * @hooked \theme\Functions->page_layer_content_end - 10
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_layer_content_end', $post->ID );
?>
<?php
/**
 * theme_page_layer_loop_end hook.
 *
 * @hooked \theme\Functions->end_page_layer_loop - 10
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_layer_loop_end', $post->ID );
?>
</div>
<?php endwhile; ?>
</div>
<?php endif; ?>
<?php
/**
 * theme_page_content_end hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_content_end', $post->ID );
?>
<?php if ( is_single() ) : ?>
<footer class="page-footer">
<div class="container">
<?php
/**
 * theme_page_footer_start hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_footer_start', $post->ID );
?>
<?php
if ( Layer::get_field( 'enable_footer_notes' ) ) :
	echo apply_filters( 'the_content', get_field( 'footer_notes' ) );
endif;
?>
<?php the_edit_link( 'page-edit-link' ); ?>
<?php
/**
 * theme_page_footer_end hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_footer_end', $post->ID );
?>
</div>
</footer>
<?php endif; ?>
<?php
/**
 * theme_page_end hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_end', $post->ID );
?>
</section>
