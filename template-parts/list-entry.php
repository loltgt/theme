<?php
/**
 * List - Entry post template part
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


$show_post_thumbnail = apply_filters( 'theme_show_post_thumbnail', true, 'list-entry' );
$post_format = get_post_format();
$post_format_data = get_post_format_data( $post_format );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( array('card', 'entry', 'list-entry') ); ?>>
<?php if ( has_filter( 'theme_entry_post_header' ) ) : ?>
<header class="card-header entry-header">
<?php
/**
 * theme_entry_post_header hook.
 */
do_action( 'theme_entry_post_header', 'list-entry' );
?>
</header>
<?php endif; ?>

<div class="card-body entry-content">
<?php the_title( '<h3 class="card-title entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
<?php
if ( $show_post_thumbnail ) :
	if ( $post_format_data ) :
		the_entry_post_format( $post_format, $post_format_data );
	elseif ( '' !== get_the_post_thumbnail() && $show_post_thumbnail ) :
		the_entry_post_thumbnail();
	endif;
else :
	the_entry_post_excerpt();
endif;
?>
<?php if ( 'post' === get_post_type() ) : ?>
<div class="entry-meta">
<?php
the_entry_post_meta();

edit_post_link();
?>
</div>
<?php endif; ?>
</div>
<?php if ( has_filter( 'theme_entry_post_footer' ) ) : ?>
<footer class="card-footer entry-footer">
<?php
/**
 * theme_entry_post_footer hook.
 */
do_action( 'theme_entry_post_footer', 'list-entry' );
?>
</footer>
<?php endif; ?>
</article>
