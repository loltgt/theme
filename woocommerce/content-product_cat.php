<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

defined( 'ABSPATH' ) || exit;
?>
<div <?php wc_product_cat_class( array('card', 'entry', 'product-entry'), $category ); ?>>
<?php
/**
 * theme_wc_subcategory_begin
 *
 * @param array $category
 * @hooked /theme/Shop_WC/wc_template_loop_product_link - 10
 */
do_action( 'theme_wc_subcategory_begin', $category );
?>
<?php if ( has_action( 'woocommerce_before_subcategory' ) ) : ?>
<header class="card-header entry-header">
<?php
/**
 * woocommerce_before_subcategory hook.
 *
 * @param array $category
 */
do_action( 'woocommerce_before_subcategory', $category );
?>
</header>
<?php endif; ?>
<?php
/**
 * woocommerce_before_subcategory_title hook.
 *
 * @param array $category
 * @hooked woocommerce_subcategory_thumbnail - 10
 */
do_action( 'woocommerce_before_subcategory_title', $category );
?>
<div class="card-body entry-content">
<?php
/**
 * woocommerce_shop_loop_subcategory_title hook.
 *
 * @param array $category
 * @hooked /theme/Shop_WC/wc_template_loop_category_link - 10
 */
do_action( 'woocommerce_shop_loop_subcategory_title', $category );

/**
 * woocommerce_after_subcategory_title hook.
 *
 * @param array $category
 */
do_action( 'woocommerce_after_subcategory_title', $category );
?>
</div>
<?php if ( has_action( 'woocommerce_after_subcategory' ) ) : ?>
<footer class="card-footer entry-footer">
<?php
/**
 * woocommerce_after_subcategory hook.
 *
 * @param array $category
 */
do_action( 'woocommerce_after_subcategory', $category );
?>
</footer>
<?php endif; ?>
<?php
/**
 * theme_wc_subcategory_end
 *
 * @param array $category
 */
do_action( 'theme_wc_subcategory_end', $category );
?>
</div>
