<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

defined( 'ABSPATH' ) || exit;
?>

<main id="main" class="site-main" role="main">
<?php
if ( is_product() ) :
	if ( is_active_sidebar( 'post-top' ) ) :
		get_template_part( 'template-parts/widgets', 'post-top' );
	endif;
else :
	if ( is_active_sidebar( 'page-top' ) ) :
		get_template_part( 'template-parts/widgets', 'page-top' );
	endif;
endif;
?>
<div id="content" class="site-content container">