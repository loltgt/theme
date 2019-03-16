<?php
/**
 * Template Name: Wizard
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer;


__( 'Wizard', 'theme' );

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
<?php
if ( is_active_sidebar( 'page-top' ) ) :
	get_template_part( 'template-parts/widgets', 'page-top' );
endif;
?>
<section id="wizard-<?php the_data_ID(); ?>" <?php the_data_class( 'wizard', array('wizard', 'page') ); ?>>
<div class="container">
<?php
while ( Layer::have_rows( 'pages' ) ) : Layer::the_row();
	$row++;

	if ( $row < $min ) 
		continue;

	if ( $row > $max )
		break;

	$page_header = Layer::get_field( 'header' );
	$page_content = Layer::get_field( 'content' );
	$page_footer = Layer::get_field( 'footer' );
?>
<?php if ( $header || $page_header ) : ?>
<header class="page-header">
<?php echo sprintf( '<h1 class="page-title">%s</h1>', ( $page_header ? $page_header : $header ) ); ?>
</header>
<?php endif; ?>
<?php if ( $content || $page_content ) : ?>
<div class="page-content">
<?php echo apply_filters( 'the_content', ( $page_content ? $page_content : $content ) ); ?>
</div>
<?php endif; ?>
<?php get_template_part( 'template-parts/form' ); ?>
<?php if ( $footer || $page_footer || $pagination || is_user_logged_in() ) : ?>
<footer class="page-footer">
<?php echo apply_filters( 'the_content', ( $page_footer ? $page_footer : $footer ) ); ?>
<?php
if ( $pagination ) :
	the_wizard_pagination( array('current' => $page, 'total' => $total) );
endif;
?>
<?php the_edit_link( 'page-edit-link' ); ?>
</footer>
<?php endif; ?>
<?php endwhile; ?>
</div>
</section>
<?php
if ( is_active_sidebar( 'page-bottom' ) ) :
	get_template_part( 'template-parts/widgets', 'page-bottom' );
endif;
?>
<?php
get_footer();