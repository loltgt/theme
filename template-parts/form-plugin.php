<?php
/**
 * Form plugin template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer;


$form_tpp = Layer::get_field( 'form_tpp' );
$form_tpp = do_shortcode( $form_tpp );

if ( $form_tpp )
	echo $form_tpp;
