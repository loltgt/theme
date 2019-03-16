<?php
/**
 * Template Modal Name: Wizard
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

get_header();

$pages = Layer::get_field( 'pages' );

if ( ! $pages )
	return;

$header = Layer::get_field( 'header' );
$content = Layer::get_field( 'content' );
$footer = Layer::get_field( 'footer' );
$pagination = Layer::get_field( 'enable_pagination' );

if ( $page = get_query_var( 'page' ) )
	$page = intval( $page );
else
	$page = 1;

$row = 0;
$total = ceil( count( $pages ) / 1 );
$min = ( ( $page * 1 ) - 1 ) + 1;
$max = ( $min + 1 ) - 1;
?>
<section id="wizard-<?php the_data_ID(); ?>" <?php the_data_class( 'wizard', array('wizard', 'page') ); ?>>
<?php
while ( Layer::have_rows( 'pages' ) ) : Layer::the_row();
	$row++;

	if ( $row < $min ) 
		continue;

	if ( $row > $max )
		break;

	$page_header = Layer::get_subfield( 'header' );
	$page_content = Layer::get_subfield( 'content' );
	$page_footer = Layer::get_subfield( 'footer' );
?>
<header class="modal-header">
<?php
/**
 * theme_modal_header_start hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_modal_header_start', $post->ID );
?>
<?php
/**
 * Filter for modal header
 *
 * @param string void
 */
echo apply_filters( 'theme_modal_header', ( $page_header ? $page_header : $header ) );
?>
<?php
/**
 * theme_print_notices hook.
 *
 * @hooked \theme\Functions->print_notices - 10
 */
do_action( 'theme_print_notices' );
?>
<?php
/**
 * theme_modal_header_end hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_modal_header_end', $post->ID );
?>
</header>
<div class="modal-body">
<?php
/**
 * theme_modal_body_end hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_modal_body_end', $post->ID );
?>
<?php
if ( $content || $page_content ) :
	/**
	 * Filter for modal body
	 *
	 * @param string void
	 */
	echo apply_filters( 'theme_modal_body', ( $page_content ? $page_content : $content ) );
endif;
?>
<?php get_template_part( 'template-parts/form' ); ?>
<?php
/**
 * theme_modal_body_end hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_modal_body_end', $post->ID );
?>
</div>
<?php if ( $footer || $page_footer || $pagination || is_user_logged_in() ) : ?>
<footer class="modal-footer">
<?php
/**
 * theme_modal_header_start hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_modal_footer_start', $post->ID );
?>
<?php
	/**
	 * Filter for modal footer
	 *
	 * @param string void
	 */
	echo apply_filters( 'theme_modal_footer', ( $page_footer ? $page_footer : $footer ) );
?>
<?php
if ( $pagination ) :
	the_wizard_pagination( array('current' => $paged, 'total' => $total) );
endif;
?>
<?php the_edit_link( 'page-edit-link' ); ?>
<?php
/**
 * theme_modal_header_start hook.
 *
 * @param int $post->ID - \WP_Post
 */
do_action( 'theme_modal_footer_end', $post->ID );
?>
</footer>
<?php endif; ?>
<?php endwhile; ?>
</section>
<?php
get_footer();