<?php
/**
 * Section content template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer;


$content = Layer::get_subfield( 'content' );
$content = apply_filters( 'the_content', $content );

if ( $content )
	echo $content;
