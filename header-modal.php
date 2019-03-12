<?php
/**
 * Header template (page mode: modal)
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<?php wp_head(); ?>
</head>

<body <?php body_class( array('modal') ); ?>>
<?php
/**
 * theme_modal_start hook.
 *
 * @param int $post->ID - WP_Post
 */
do_action( 'theme_modal_start', $post->ID );
?>
<div <?php the_data_class( 'modal_dialog', array('modal-dialog', 'modal-lg') ); the_data_extras( 'modal_dialog', '', array('role' => 'document') ); ?>>
<div class="modal-content">
<?php if ( is_page_template( 'default' ) ) : ?>
<div class="modal-header">
<?php the_title( '<h5 class="modal-title">', '</h5>' ); ?>
<?php
/**
 * theme_print_notices hook.
 *
 * @hooked /theme/Functions/print_notices - 10
 */
do_action( 'theme_print_notices' );
?>
<?php the_modal_close_button(); ?>
</div>
<?php endif;