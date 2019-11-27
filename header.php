<?php
/**
 * Header template
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


$has_nav_menu = has_nav_menu( 'header' );
$has_widgets_before_menu = is_active_sidebar( 'navigation-primary-before' );
$has_widgets_after_menu = is_active_sidebar( 'navigation-primary-after' );
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">

<?php if ( $has_nav_menu || $has_widgets_before_menu || $has_widgets_after_menu ) : ?>

<header id="header" class="site-header">
<?php
if ( $has_widgets_before_menu ) :
	get_template_part( 'template-parts/widgets', 'navigation-primary-before' );
endif;
?>
<?php
if ( $has_nav_menu ) :
	get_template_part( 'template-parts/navigation', 'primary' );
endif;
?>
<?php
if ( $has_widgets_after_menu ) :
	get_template_part( 'template-parts/widgets', 'navigation-primary-after' );
endif;
?>
</header>
<?php endif; ?>

<div id="container" class="site-container">
