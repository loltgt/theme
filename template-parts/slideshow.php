<?php
/**
 * Slideshow template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use theme\Layer;


$enable_tpp = Layer::get_field( 'enable_slideshow_tpp' );

if ( ! $enable_tpp && ! Layer::get_field( 'slides' ) )
	return;

$slideshow_id = get_data_ID( 'slideshow' );
?>
<div id="slideshow-<?php the_data_ID( 'slideshow' ); ?>" <?php the_data_class( 'slideshow', 'slideshow' ); the_data_extras( 'slideshow' ); ?>>
<?php
/**
 * theme_before_slideshow hook.
 *
 * @hooked /theme/Functions->before_slideshow - 10
 *
 * @param string|int $slideshow_id
 */
do_action( 'theme_before_slideshow', $slideshow_id );
?>
<?php get_template_part( 'template-parts/slideshow', ( $enable_tpp ? 'plugin' : 'block' ) ); ?>
<?php
/**
 * theme_after_slideshow hook.
 *
 * @param string|int $slideshow_id
 */
do_action( 'theme_after_slideshow', $slideshow_id );
?>
</div>
