<?php
/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
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
 * @version 3.0.9
 */

defined( 'ABSPATH' ) || exit;

if ( ! WC()->cart->needs_shipping_address() )
	return;
?>
<div class="woocommerce-shipping-fields">
<div class="shipping_address">

<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

<div class="row woocommerce-shipping-fields__field-wrapper">
	<?php
		$fields = $checkout->get_checkout_fields( 'shipping' );

		foreach ( $fields as $key => $field ) {
			if ( isset( $field['country_field'], $fields[ $field['country_field'] ] ) ) {
				$field['country'] = $checkout->get_value( $field['country_field'] );
			}
			woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
		}
	?>
</div>

<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

</div>
</div>
