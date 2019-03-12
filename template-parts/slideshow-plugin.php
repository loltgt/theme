<?php
/**
 * Slideshow plugin template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer;


$slideshow_tpp = Layer::get_field( 'slideshow_tpp' );
$slideshow_tpp = do_shortcode( $slideshow_tpp );

if ( $slideshow_tpp )
	echo $slideshow_tpp;
