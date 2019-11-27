<?php
/**
 * Page template part
 *
 *
 * @global null|object $post - \WP_Post
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


global $post;
?>
<section id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php
/**
 * theme_page_start hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_start', $post->ID );
?>
<?php
/**
 * theme_page_header filter.
 *
 * @param bool void - false
 * @param int $post->ID - \WP_Post
 */
if ( apply_filters( 'theme_page_header', false, $post->ID ) ) :
?>
<header class="page-header">
<?php
/**
 * theme_page_header_start hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_header_start', $post->ID );
?>
<?php
/**
 * theme_page_header_end hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_header_end', $post->ID );
?>
</header>
<?php endif; ?>
<div class="page-content">
<?php the_content(); ?>
</div>
<?php
/**
 * theme_page_footer filter.
 *
 * @param bool void - false
 * @param int $post->ID - \WP_Post
 */
if ( apply_filters( 'theme_page_footer', false, $post->ID ) ) :
?>
<footer class="page-footer">
<?php
/**
 * theme_page_footer_start hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_page_footer_start', $post->ID );
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
