<?php
/**
 * Section post template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Functions;
use \theme\Layer;


$_posts = Layer::get_subfield( 'post' );

if ( empty( $_posts ) )
	return;

$post = $_posts[0];

setup_postdata( $post );

$post_type = get_post_type( $post );

if ( Functions::has_shop( 'WooCommerce' ) && $post_type === 'product' ) :

	wc_get_template_part( 'content', 'single-product' );

elseif ( $post_type === 'post' ) :
	get_template_part( 'template-parts/post', get_post_format() );
else :
	get_template_part( 'template-parts/' . $post_type, \theme\get_page_template_name() );
endif;

wp_reset_postdata();
