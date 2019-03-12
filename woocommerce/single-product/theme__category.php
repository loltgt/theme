<?php
/**
 * Single product category
 *
 * @package theme
 */

defined( 'ABSPATH' ) || exit;

global $product;

$product_category_list = wc_get_product_category_list(
	$product->get_id(),
	'',
	'<ul class="nav product-categories">',
	'</ul>'
);

if ( ! $product_category_list )
	return;

$product_category_list = str_replace(
	array( '<a' ),
	array( '<li class="nav-item"><a class="nav-link"' ),
	$product_category_list
);
?>
<div class="product-categories posted_in row">
	<span class="sr-only sr-only-focusable product-categories-label"><?php _e( 'Category', 'woocommerce' ); ?></span>
	<?php echo $product_category_list; ?>
</div>
