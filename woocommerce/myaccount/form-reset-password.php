<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.5
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_reset_password_form' );
?>

<div class="u-columns row" id="customer_reset_password">

	<div class="u-column1 col-md-5 col-lg-6">

		<h2><span class="icon icon-key" aria-hidden="true"></span><span class="label"><?php esc_html_e( 'Reset password', 'woocommerce' ); ?></span></h2>

		<form method="post" class="woocommerce-ResetPassword lost_reset_password">

			<p><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'woocommerce' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>

			<p class="woocommerce-form-row woocommerce-form-row--first form-group">
				<label for="password_1" class="sr-only" aria-hidden="true"><?php esc_html_e( 'New password', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="password" class="form-control woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" placeholder="<?php esc_attr_e( 'New password', 'woocommerce' ); ?>" required />
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--last form-group">
				<label for="password_2" class="sr-only" aria-hidden="true"><?php esc_html_e( 'Re-enter new password', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="password" class="form-control woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" placeholder="<?php esc_attr_e( 'Re-enter new password', 'woocommerce' ); ?>" required />
			</p>

			<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
			<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />

			<div class="clear"></div>

			<?php do_action( 'woocommerce_resetpassword_form' ); ?>

			<p class="woocommerce-form-row form-group">
				<input type="hidden" name="wc_reset_password" value="true" />
				<button type="submit" class="btn btn-secondary woocommerce-Button button" value="<?php esc_attr_e( 'Save', 'woocommerce' ); ?>"><?php esc_html_e( 'Save', 'woocommerce' ); ?></button>
			</p>

			<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

		</form>

	</div>

	<div class="u-column2 col-md-7 col-lg-6">
<?php wc_get_template_part( 'myaccount/theme__customer-account-note' ); ?>
	</div>

</div>
<?php
do_action( 'woocommerce_after_reset_password_form' );
