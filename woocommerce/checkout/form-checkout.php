<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<h3 id="order_review_heading" class="sr-only"><?php _e( 'Your order', 'woocommerce' ); ?></h3>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

	<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
		<div class="woocommerce-account-fields">
			<?php if ( ! $checkout->is_registration_required() ) : ?>

				<p class="form-group create-account">
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
						<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ) ?> type="checkbox" name="createaccount" value="1" /> <span><?php _e( 'Create an account?', 'woocommerce' ); ?></span>
					</label>
				</p>

			<?php endif; ?>

			<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

			<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

				<div class="create-account">
					<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
						<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
					<?php endforeach; ?>
					<div class="clear"></div>
				</div>

			<?php endif; ?>

			<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
		</div>
	<?php endif; ?>

		<div id="customer_details">
			<div class="woocommerce-fields">
			<?php if ( wc_ship_to_billing_address_only() || ! WC()->cart->needs_shipping() ) : ?>
				<h3><?php _e( 'Billing &amp; Shipping', 'woocommerce' ); ?></h3>

				<?php do_action( 'woocommerce_checkout_billing' ); ?>
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			<?php else : ?>
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item">
						<a id="checkout-billing-tab" class="nav-link active" data-toggle="tab" href="#checkout-billing-panel" role="tab"><?php _e( 'Billing details', 'woocommerce' ); ?></a>
					</li>
					<li class="nav-item">
						<a id="checkout-shipping-tab" class="nav-link" data-toggle="tab" href="#checkout-shipping-panel" role="tab"><?php _e( 'Shipping address', 'woocommerce' ); ?></a>
					</li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane active" id="checkout-billing-panel" role="tabpanel" aria-labelledby="checkout-billing-tab" aria-expanded="true">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					</div>

					<div class="tab-pane" id="checkout-shipping-panel" role="tabpanel" aria-labelledby="checkout-shipping-tab" aria-expanded="false">
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					</div>
				</div>
			<?php endif; ?>
			</div>

			<div class="woocommerce-additional-fields">
				<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

				<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>
					<h3><?php _e( 'Additional information', 'woocommerce' ); ?></h3>

					<div class="woocommerce-additional-fields__field-wrapper">
						<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
							<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
						<?php endforeach; ?>
					</div>

				<?php endif; ?>

				<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
