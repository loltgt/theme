<?php
/**
 * Entry post template part
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


/**
 * theme_show_post_thumbnail filter.
 *
 * @param bool void - true
 * @param string void - ‘context‘
 */
$show_post_thumbnail = apply_filters( 'theme_show_post_thumbnail', true, 'entry' );

$post_format = get_post_format();
$post_format_data = get_post_format_data( $post_format );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( array('card', 'entry') ); ?>>
<?php the_entry_post_click_target(); ?>
<?php if ( has_filter( 'theme_entry_post_header' ) ) : ?>
<header class="card-header entry-header">
<?php
/**
 * theme_entry_post_header hook.
 *
 * @param string void
 */
do_action( 'theme_entry_post_header', 'entry' );
?>
</header>
<?php endif; ?>
<?php
if ( $post_format_data ) :
	the_entry_post_format( $post_format, $post_format_data );
elseif ( empty( get_the_post_thumbnail() ) && $show_post_thumbnail ) :
	the_entry_post_thumbnail();
endif;
?>
<div class="card-body entry-content">
<?php the_title( '<h3 class="card-title entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
<?php
if ( ! $post_format_data ) :
	the_entry_post_excerpt();
endif;
?>
<?php if ( 'post' === get_post_type() ) : ?>
<div class="entry-meta">
<?php the_entry_post_meta(); ?>
</div>
<?php endif; ?>
</div>
<?php if ( has_filter( 'theme_entry_post_footer' ) ) : ?>
<footer class="card-footer entry-footer">
<?php
/**
 * theme_entry_post_footer hook.
 *
 * @param string void
 */
do_action( 'theme_entry_post_footer', 'entry' );
?>
</footer>
<?php endif; ?>
</article>
