<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() || ! empty( WC()->cart->applied_coupons ) ) { // @codingStandardsIgnoreLine.
	return;
}

$info_message = apply_filters( 'woocommerce_checkout_coupon_message', '<h5>' . __( 'Have a coupon?', 'woocommerce' ) . '</h5> <a href="#checkout-coupon" class="btn btn-secondary btn-sm showcoupon" data-toggle="collapse" aria-expanded="false">' . __( 'Click here to enter your code', 'woocommerce' ) . '</a>' );
?>

<div class="woocommerce-checkout-coupon">
<p><?php echo $info_message; ?></p>
<div id="checkout-coupon" class="collapse checkout_coupon">
<div class="input-group">
	<input type="text" name="coupon_code" class="form-control input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value="" />
	<div class="input-group-append">
		<button type="submit" class="btn btn-primary btn-sm btn-apply-coupon button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
	</div>
</div>
</div>
</div>
