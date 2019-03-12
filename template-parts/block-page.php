<?php
/**
 * Section page template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer;


$post = Layer::get_subfield( 'page' );

if ( ! $post )
	return;

setup_postdata( $post );

get_template_part( 'template-parts/page', \theme\get_page_template_name() );

wp_reset_postdata();
