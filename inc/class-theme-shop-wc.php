<?php
/**
 * theme shop WooCommerce
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Theme;


/**
 * Shop WooCommerce class
 */
class Shop_WC {

	// @type object $theme - \theme\Theme
	private $theme;


	/**
	 * Function __construct
	 */
	function __construct() {

		if ( ! defined( 'WC_VERSION' ) )
			return;

		$this->theme = Theme::instance();

		Theme::set( "shop", 'WooCommerce' );

		add_action( 'init', array($this, 'wc_theme_cleanup') );
		add_action( 'after_setup_theme', array($this, 'wc_theme_supports') );
		add_action( 'wp_enqueue_scripts', array($this, 'wc_theme_assets_queue') );

		// Template: ./theme/woocommerce/archive-product.php
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
		add_action( 'woocommerce_shop_loop', array($this, 'wc_shop_loop_ajax_add_to_cart'), 0 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count' );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering' );

		// Template: ./theme/woocommerce/content-product.php
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
		add_action( 'theme_wc_shop_loop_item_begin', array($this, 'wc_template_loop_product_link'), 10 );
		add_action( 'woocommerce_shop_loop_item_title', array($this, 'wc_template_loop_product_edit_link'), 5 );
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
		add_action( 'woocommerce_shop_loop_item_title', array($this, 'wc_template_loop_product_title'), 10 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
		add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 15 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

		// Template: ./theme/woocommerce/content-product_cat.php
		remove_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
		remove_action( 'woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10 );
		add_action( 'theme_wc_subcategory_begin', array($this, 'wc_template_loop_category_link'), 10 );
		remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
		add_action( 'woocommerce_before_subcategory_title', array($this, 'wc_template_loop_category_thumbnail'), 10  );
		remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
		add_action( 'woocommerce_shop_loop_subcategory_title', array($this, 'wc_template_loop_category_title'), 10 );

		// Template: ./theme/woocommerce/content-single-product.php
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_show_product_sale_flash', 5 );
		add_action( 'woocommerce_single_product_summary', array($this, 'wc_template_single_product_category'), 15 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		add_action( 'woocommerce_after_add_to_cart_quantity', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
		add_action( 'woocommerce_after_add_to_cart_quantity', 'woocommerce_single_variation', 0 );
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
		add_action( 'woocommerce_after_single_product_summary', 'woocommerce_show_product_images', 0 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		add_action( 'woocommerce_product_additional_information', 'woocommerce_template_single_meta', 40 );
		add_action( 'woocommerce_after_single_product_summary', array($this, 'wc_template_single_product_description'), 5 );

		// Template: ./theme/woocommerce/cart/cart.php
		add_action( 'woocommerce_after_cart_table', array($this, 'wc_template_cart_actions') );
		add_action( 'woocommerce_proceed_to_checkout', array($this, 'wc_cart_action_button_return_to_shop'), 0 );
		add_action( 'woocommerce_proceed_to_checkout', array($this, 'wc_cart_action_button_update_cart'), 5 );

		// Template: ./theme/woocommerce/checkout/checkout.php
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
		add_action( 'woocommerce_checkout_after_customer_details', 'woocommerce_checkout_coupon_form', 10 );
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
		add_action( 'woocommerce_checkout_after_order_review', 'woocommerce_checkout_payment', 20 );

		/**
		 * Disables default stylesheet
		 *
		 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
		 */
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

		add_filter( 'wc_get_template_part', array($this, 'wc_get_template_part_avoid_break'), 9999 );
		add_filter( 'woocommerce_add_error', array($this, 'wc_notice_button_class') );
		add_filter( 'woocommerce_add_success', array($this, 'wc_notice_button_class') );
		add_filter( 'woocommerce_add_notice', array($this, 'wc_notice_button_class') );
		add_filter( 'woocommerce_add_success', array($this, 'wc_notice_restore_item_button') );
		add_filter( 'woocommerce_loop_add_to_cart_link', array($this, 'wc_loop_product_add_to_cart_button_class') );
		add_filter( 'woocommerce_product_get_image', array($this, 'wc_loop_product_thumbnail_class') );
		add_filter( 'woocommerce_single_product_image_gallery_classes', array($this, 'wc_single_product_image_gallery_classes') );
		remove_filter( 'woocommerce_product_tabs', 'woocommerce_default_product_tabs' );
		add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );
		add_filter( 'woocommerce_product_tabs', array($this, 'wc_custom_product_tabs') );
		add_filter( 'woocommerce_form_field_args' , array($this, 'wc_form_field_args') );
		add_filter( 'woocommerce_checkout_fields' , array($this, 'wc_checkout_fields') );
		add_filter( 'woocommerce_account_menu_items', array($this, 'wc_account_menu_items_remove_logout') );
		add_filter( 'woocommerce_breadcrumb_defaults', array($this, 'wc_breadcrumb_defaults') );
		add_filter( 'woocommerce_breadcrumb_home_url', array($this, 'wc_breadcrumb_home_url') );

		Theme::register( "Shop_WC", $this, "shop" );

	}



	/**
	 * Some theme shop clean-up
	 */
	public function wc_theme_cleanup() {
		// Disables the gallery noscript
		remove_action( 'wp_head', 'wc_gallery_noscript' );
	}


	/**
	 * Adds default shop theme supports
	 *
	 * @see /WC_Frontend_Scripts::load_scripts()
	 */
	public function wc_theme_supports() {
		// Adds shop support
		add_theme_support( 'woocommerce' );

		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-slider' );
		add_theme_support( 'wc-product-gallery-lightbox' );

		/**
		 * Force remove superseding of the build-in jQuery library,
		 * compatibility workaround for wc-product-gallery-slider, wc-product-gallery-lightbox
		 */
		add_filter( 'theme_supersedes_bi_jquery', '__return_false' );
	}


	/**
	 * Adds default shop assets queue
	 */
	public function wc_theme_assets_queue() {
		wp_dequeue_style( 'woocommerce-inline' );
	}


	/**
	 * Prevents fatal errors when 3rd party plugins filters the template file
	 *
	 * @see wc_get_template_part()
	 *
	 * @global object $wp_filter - WP_Hook
	 * @param mixed $template
	 * @return mixed $template|void
	 */
	public function wc_get_template_part_avoid_break( $template ) {
		global $wp_filter;

		if ( count( $wp_filter['wc_get_template_part']->callbacks ) === 1 )
			return $template;

		if ( $template ) {
			if ( is_string( $template ) ) {
				if ( ! file_exists( $template ) )
					return false;
			} else if ( is_array( $template ) ) {
				foreach ( $template as $tpl ) {
					if ( ! file_exists( $tpl ) )
						return false;
				}
			}

			return $template;
		}

		return false;
	}


	/**
	 * Product content card click target
	 */
	public function wc_template_loop_product_link() {
		echo "<a href=\"" . esc_attr( esc_url( get_the_permalink() ) ) . "\"",
			 " class=\"card-click-target click-target",
			 " woocommerce-LoopProduct-link woocommerce-loop-product__link\"",
			 " aria-hidden=\"true\"></a>\n";
	}


	/**
	 * Product content card edit link
	 */
	public function wc_template_loop_product_edit_link() {
		echo get_the_edit_link( 'woocommerce-loop-product__edit-link' );
	}


	/**
	 * Product content card title
	 *
	 * @see woocommerce_template_loop_product_title()
	 */
	public function wc_template_loop_product_title() {
		echo "<h2 class=\"woocommerce-loop-product__title\">",
			 "<a href=\"" . esc_attr( esc_url( get_the_permalink() ) ) . "\">",
			 get_the_title() . "</a></h2>\n";
	}


	/**
	 * Product content card subcategory click target
	 *
	 * @param object $category
	 */
	public function wc_template_loop_category_link( $category ) {
		echo "<a href=\"" . esc_attr( esc_url( get_term_link( $category, 'product_cat' ) ) ) . "\"",
			 " class=\"card-click-target click-target",
			 " woocommerce-LoopProductCategory-link woocommerce-loop-product_cat__link\"",
			 " aria-hidden=\"true\"></a>\n";
	}


	/**
	 * Product content card subcategory thumbnail
	 *
	 * @see woocommerce_subcategory_thumbnail()
	 *
	 * @param object $category
	 */
	public function wc_template_loop_category_thumbnail( $category ) {
		ob_start();

		woocommerce_subcategory_thumbnail( $category );

		$thumb = apply_filters( 'post_thumbnail_html', ob_get_clean() );
		$thumb = str_replace( 'src="', 'class="attachment-shop_catalog" src="', $thumb );
		$thumb = apply_filters( 'woocommerce_product_get_image', $thumb );

		echo $thumb;
	}


	/**
	 * Product content card subcategory title
	 *
	 * @see woocommerce_template_loop_category_title()
	 *
	 * @param object $category
	 */
	public function wc_template_loop_category_title( $category ) {
		$count_html = '';

		if ( $category->count > 0 ) :
			$count_html = apply_filters(
				'woocommerce_subcategory_count_html',
				' <mark class="count">(' . esc_html( $category->count ) . ')</mark>',
				$category
			);
		endif;

		echo "<h2 class=\"woocommerce-loop-category__title\">",
			 "<a href=\"" . esc_attr( esc_url( get_term_link( $category, 'product_cat' ) ) ) . "\">",
			 esc_html( $category->name ),
			 $count_html,
			 "</a></h2>\n";
	}


	/**
	 * Adds classname(s) to notice buttons
	 *
	 * @see wc_print_notice()
	 *
	 * @param string $message
	 * @return string void
	 */
	public function wc_notice_button_class( $message ) {
		return str_replace( 'button', 'btn btn-sm btn-notice button', $message );
	}


	/**
	 * Replacement for remove item notice buttons
	 *
	 * @see wc_print_notice()
	 *
	 * @param string $message
	 * @return string $message
	 */
	public function wc_notice_restore_item_button( $message ) {
		if ( ! isset( $_GET['remove_item'] ) )
			return $message;

		$message = str_replace(
			array('restore-item', '?'),
			array('btn btn-sm btn-notice restore-item', ''),
			$message
		);

		return $message;
	}


	/**
	 * Adds classname(s) to the content card add to cart button
	 *
	 * @see ?/woocommerce/loop/add-to-cart.php
	 *
	 * @param string $html
	 * @return string void
	 */
	public function wc_loop_product_add_to_cart_button_class( $html ) {
		return str_replace( 'class="', 'class="btn btn-primary btn-sm ', $html );
	}


	/**
	 * Adds a classname to the content card thumbnail
	 *
	 * @see /WC_Product->get_image()
	 *
	 * @param string $html
	 * @return string $html|void
	 */
	public function wc_loop_product_thumbnail_class( $html ) {
		if ( is_cart() )
			return $html;

		return str_replace( 'class="', 'class="card-img-top ', $html );
	}


	/**
	 * Shows ajax add to cart buttons only to the archive page
	 *
	 * @see ?/woocommerce/loop/add-to-cart.php
	 *
	 * @return void
	 */
	public function wc_shop_loop_ajax_add_to_cart() {
		if ( 'no' === get_option( 'woocommerce_enable_ajax_add_to_cart' ) )
			return;

		add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 15 );
	}


	/**
	 * Filters the wrapper classes for the single product image
	 *
	 * @see ?/woocommerce/single-product/product-image.php
	 *
	 * @param array $wrapper_classes
	 * @return array $wrapper_classes
	 */
	public function wc_single_product_image_gallery_classes( $wrapper_classes ) {
		$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
		$wrapper_classes[] = "col-md-{$columns}";
		return (array) $wrapper_classes;
	}


	/**
	 * Displays the single product category template
	 *
	 * @see ?/woocommerce/content-single-product.php
	 */
	public function wc_template_single_product_category() {
		wc_get_template( 'single-product/theme__category.php' );
	}


	/**
	 * Displays the single product description template
	 *
	 * @see wc_display_product_attributes()
	 * @see ?/woocommerce/single-product/product-attributes.php
	 */
	public function wc_template_single_product_description() {
		wc_get_template( 'single-product/theme__description.php' );
	}


	/**
	 * Custom product tabs in product pages
	 *
	 * @see ?/woocommerce/single-product/tabs/tabs.php
	 *
	 * @global object $product - WC_Product
	 * @global object $post - WP_Post
	 * @param array $tabs
	 * @return array $tabs
	 */
	public function wc_custom_product_tabs( $tabs = array() ) {
		global $product, $post;

		// Additional information tab - shows attributes.
		if ( $product ) {
			$tabs['additional_information'] = array(
				'title'    => __( 'Additional information', 'woocommerce' ),
				'priority' => 20,
				'callback' => 'woocommerce_product_additional_information_tab',
			);
		}

		// Reviews tab - shows comments.
		if ( comments_open() ) {
			$tabs['reviews'] = array(
				'title'    => sprintf( __( 'Reviews (%d)', 'woocommerce' ), $product->get_review_count() ),
				'priority' => 30,
				'callback' => 'comments_template',
			);
		}

		return $tabs;
	}


	/**
	 * Displays the cart actions template
	 *
	 * @see ?/woocommerce/cart/cart.php
	 */
	public function wc_template_cart_actions() {
		wc_get_template( 'cart/theme__cart-actions.php' );
	}


	/**
	 * Cart action button: return to shop
	 *
	 * @see ./theme/woocommerce/theme__cart-actions.php
	 */ 
	public function wc_cart_action_button_return_to_shop() {
		$href = esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) );

		echo "<a class=\"btn btn-secondary btn-sm btn-shop button wc-backward\" href=\"{$href}\">",
			 esc_html_e( 'Return to shop', 'woocommerce' ) . "</a>\n";
	}


	/**
	 * Cart action button: update cart
	 *
	 * @see ./theme/woocommerce/theme__cart-actions.php
	 */ 
	public function wc_cart_action_button_update_cart() {
		echo "<button type=\"submit\" class=\"btn btn-secondary btn-sm btn-update-cart button\" ",
			 "name=\"update_cart\" value=\"" . esc_attr_e( 'Update cart', 'woocommerce' ) . "\">",
			 esc_html_e( 'Update cart', 'woocommerce' ) . "</button>\n";
	}


	/**
	 * Adds column classes to form fields for compatibity with the css framework
	 *
	 * @see woocommerce_form_field()
	 *
	 * @param array $args
	 * @return array $args
	 */
	public function wc_form_field_args( $args ) {
		if ( $args['type'] == 'checkbox' )
			$args['input_class'][] = 'form-check-input';
		else if ( $args['type'] == 'radio' )
			$args['input_class'][] = 'form-radio-input';
		else
			$args['input_class'][] = 'form-control';

		if ( $args['type'] == 'select' || $args['type'] == 'country' || $args['type'] == 'state' )
			$args['input_class'][] = 'custom-select';

		return $args;
	}


	/**
	 * Adds column classes to checkout fields for compatibity with the css framework
	 *
	 * @see /WC_Checkout->get_checkout_fields()
	 * 
	 * @param array $fields
	 * @return array $fields
	 */
	public function wc_checkout_fields( $fields ) {
		$fields['billing']['billing_first_name']['class'][] = 'col-md-6';
		$fields['billing']['billing_last_name']['class'][] = 'col-md-6';
		$fields['billing']['billing_company']['class'][] = 'col-md-6';
		$fields['billing']['billing_address_1']['class'][] = 'col-md-6';
		$fields['billing']['billing_address_2']['class'][] = 'col-md-6';
		$fields['billing']['billing_city']['class'][] = 'col-md-6';
		$fields['billing']['billing_postcode']['class'][] = 'col-md-6';
		$fields['billing']['billing_country']['class'][] = 'col-md-6';
		$fields['billing']['billing_state']['class'][] = 'col-md-6';
		$fields['billing']['billing_email']['class'][] = 'col-md-6';
		$fields['billing']['billing_phone']['class'][] = 'col-md-6';

		$fields['shipping']['shipping_first_name']['class'][] = 'col-md-6';
		$fields['shipping']['shipping_last_name']['class'][] = 'col-md-6';
		$fields['shipping']['shipping_company']['class'][] = 'col-md-6';
		$fields['shipping']['shipping_address_1']['class'][] = 'col-md-6';
		$fields['shipping']['shipping_address_2']['class'][] = 'col-md-6';
		$fields['shipping']['shipping_city']['class'][] = 'col-md-6';
		$fields['shipping']['shipping_postcode']['class'][] = 'col-md-6';
		$fields['shipping']['shipping_country']['class'][] = 'col-md-6';
		$fields['shipping']['shipping_state']['class'][] = 'col-md-6';

		$fields['account']['account_username']['class'][] = 'col-md-6';
		$fields['account']['account_password']['class'][] = 'col-md-6';
		$fields['account']['account_password-2']['class'][] = 'col-md-6';

		return (array) $fields;
	}


	/**
	 * Removes the logout item from myaccount menu
	 *
	 * @see wc_get_account_menu_items()
	 *
	 * @param array $items
	 * @return array $items
	 */
	public function wc_account_menu_items_remove_logout( $items ) {
		unset( $items['customer-logout'] );
		return $items;
	}


	/**
	 * Filters the breadcrumb, 
	 * it removes the delimiter to match css framework behaviour and fixes shop title and url
	 *
	 * @see woocommerce_breadcrumb()
	 *
	 * @param array $args
	 * @return array $args
	 */
	public function wc_breadcrumb_defaults( $args ) {
		$args['delimiter'] = '';

		$shop_id = wc_get_page_id( 'shop' );
		$shop_url = get_permalink( $shop_id );

		if ( $shop_url === site_url( $_SERVER['REQUEST_URI'] ) )
			$args['home'] = '';
		elseif ( $shop_url !== home_url() )
			$args['home'] = get_the_title( $shop_id );

		return $args;
	}


	/**
	 * Filters breadcrumb url and shop url
	 *
	 * @see woocommerce_breadcrumb()
	 *
	 * @param string $url
	 * @return string $url
	 */
	public function wc_breadcrumb_home_url( $url ) {
		$shop_url = get_permalink( wc_get_page_id( 'shop' ) );

		if ( $shop_url !== home_url() )
			$url = $shop_url;

		return $url;
	}


}

new Shop_WC;