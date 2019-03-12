<?php
/**
 * Shop breadcrumb
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/breadcrumb.php.
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
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $breadcrumb ) ) {
	return;
}
?>

<?php echo $wrap_before; ?>

<ol class="breadcrumb">
<?php foreach ( $breadcrumb as $key => $crumb ) : ?>
<?php echo $before; ?>
<?php
	if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) :
		echo "\t<li class=\"breadcrumb-item\">\n",
			 "\t\t<a href=\"" . esc_url( $crumb[1] ) . "\">" . esc_html( $crumb[0] ) . "</a>\n\t</li>\n";
	else :
		echo "\t<li class=\"breadcrumb-item active\">" . esc_html( $crumb[0] ) . "</li>\n";
	endif;
?>
<?php echo $after; ?>
<?php
if ( sizeof( $breadcrumb ) !== $key + 1 )
	echo $delimiter;
?>
<?php endforeach; ?>
</ol>
<?php echo $wrap_after; ?>

