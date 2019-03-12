<?php
/**
 * Checkout actions
 *
 * @package theme
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="actions">
<?php if ( wc_coupons_enabled() ) : ?>
<div class="coupon">
	<label for="coupon_code"><?php _e( 'Coupon:', 'woocommerce' ); ?></label>
	<div class="input-group">
		<input type="text" name="coupon_code" class="form-control input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
		<div class="input-group-append">
			<button type="submit" class="btn btn-secondary btn-sm btn-apply-coupon button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
		</div>
	</div>
	<?php do_action( 'woocommerce_cart_coupon' ); ?>
</div>
<?php endif; ?>

<?php do_action( 'woocommerce_cart_actions' ); ?>

<div class="wc-proceed-to-checkout">
<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
</div>

<?php wp_nonce_field( 'woocommerce-cart' ); ?>
</div>
