<?php
/**
 * Login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/form-login.php.
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

if ( is_user_logged_in() ) {
	return;
}
?>
<form class="woocommerce-form woocommerce-form-login login" method="post" <?php echo ( $hidden ) ? 'style="display:none;"' : ''; ?>>

	<?php do_action( 'woocommerce_login_form_start' ); ?>

	<?php echo ( $message ) ? wpautop( wptexturize( $message ) ) : ''; // @codingStandardsIgnoreLine ?>

	<p class="form-group small">
		<label for="username" class="sr-only"><?php esc_html_e( 'Username or email', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input class="form-control form-control-sm input-text" type="text" name="username" id="username" placeholder="<?php esc_attr_e( 'Username or email', 'woocommerce' ); ?>" required />
	</p>
	<p class="form-group small">
		<label for="password" class="sr-only"><?php esc_html_e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
		<input class="form-control form-control-sm input-text" type="password" name="password" id="password" placeholder="<?php esc_attr_e( 'Password', 'woocommerce' ); ?>" required />
	</p>

	<?php do_action( 'woocommerce_login_form' ); ?>

	<p class="form-group small">
		<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
		<button type="submit" class="btn btn-sm btn-secondary button" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>"><?php esc_html_e( 'Login', 'woocommerce' ); ?></button>
		<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ) ?>" />
		<label class="form-check float-right woocommerce-form__label woocommerce-form__label-for-checkbox inline">
			<input class="form-check-input form-woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span class="form-check-label"><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
		</label>
	</p>
	<p class="lost_password small">
		<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
	</p>

	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form_end' ); ?>

</form>
