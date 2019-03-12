<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-end.php.
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
</div>
<?php
if ( is_product() ) :
	if ( is_active_sidebar( 'post-bottom' ) ) :
		get_template_part( 'template-parts/widgets', 'post-bottom' );
	endif;
else :
	/**
	 * Hook: woocommerce_sidebar.
	 *
	 * @hooked woocommerce_get_sidebar - 10
	 */
	do_action( 'woocommerce_sidebar' );

	if ( is_active_sidebar( 'page-bottom' ) ) :
		get_template_part( 'template-parts/widgets', 'page-bottom' );
	endif;
endif;
?>
</main>
