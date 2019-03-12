<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

namespace theme;

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<div <?php post_class( array('card', 'entry', 'product-entry') ); ?>>
<?php
/**
 * theme_wc_shop_loop_item_begin hook.
 *
 * @param object $product - WC_Product
 * @hooked /theme/Shop_WC/wc_template_loop_category_link - 10
 */
do_action( 'theme_wc_shop_loop_item_begin', $product );
?>
<?php if ( has_action( 'woocommerce_before_shop_loop_item' ) ) : ?>
<header class="card-header entry-header">
<?php
/**
 * woocommerce_before_shop_loop_item hook.
 */
do_action( 'woocommerce_before_shop_loop_item' );
?>
</header>
<?php endif; ?>
<?php
/**
 * woocommerce_before_shop_loop_item_title hook.
 *
 * @hooked woocommerce_show_product_loop_sale_flash - 10
 * @hooked woocommerce_template_loop_product_thumbnail - 10
 */
do_action( 'woocommerce_before_shop_loop_item_title' );
?>
<div class="card-body entry-content">
<?php
/**
 * woocommerce_shop_loop_item_title hook.
 *
 * @hooked /theme/Shop_WC/wc_template_loop_edit_link - 5
 * @hooked /theme/Shop_WC/wc_template_loop_product_title - 10
 * @hooked woocommerce_template_loop_rating - 15
 */
do_action( 'woocommerce_shop_loop_item_title' );
?>
<?php if ( has_filter( 'woocommerce_after_shop_loop_item_title' ) ) : ?>
<div class="card-text">
<?php
/**
 * woocommerce_after_shop_loop_item_title hook.
 *
 * @hooked woocommerce_template_loop_price - 10
 * @hooked woocommerce_template_loop_add_to_cart - 15
 */
do_action( 'woocommerce_after_shop_loop_item_title' );
?>
</div>
<?php endif; ?>
</div>
<?php if ( has_action( 'woocommerce_after_shop_loop_item' ) ) : ?>
<footer class="card-footer entry-footer">
<?php
/**
 * woocommerce_after_shop_loop_item hook.
 */
do_action( 'woocommerce_after_shop_loop_item' );
?>
</footer>
<?php endif; ?>
<?php
/**
 * theme_wc_shop_loop_item_end hook.
 *
 * @param object $product - WC_Product
 */
do_action( 'theme_wc_shop_loop_item_end', $product );
?>
</div>
