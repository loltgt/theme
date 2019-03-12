<?php
/**
 * Section shortcode template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer;


$shortcode = Layer::get_subfield( 'shortcode' );
$shortcode = do_shortcode( $shortcode );

if ( $shortcode )
	echo $shortcode;
