<?php
/**
 * Header template (default)
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

<body <?php body_class(); ?>>
<div id="page" class="site">

<header id="header" class="site-header" role="banner">
<?php
if ( is_active_sidebar( 'navigation-primary-before' ) ) :
	get_template_part( 'template-parts/widgets', 'navigation-primary-before' );
endif;
?>
<?php get_template_part( 'template-parts/navigation', 'primary' ); ?>
<?php
if ( is_active_sidebar( 'navigation-primary-after' ) ) :
	get_template_part( 'template-parts/widgets', 'navigation-primary-after' );
endif;
?>
</header>

<div id="container" class="site-container">
<?php
/**
 * theme_print_notices hook.
 *
 * @hooked /theme/Functions->print_notices - 10
 */
do_action( 'theme_print_notices' );
