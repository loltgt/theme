<?php
/**
 * theme functions
 *
 * @package theme
 * @version 2.0
 */

namespace theme;

use \theme\Theme;


/**
 * Functions class
 */
class Functions {

	// @type object $theme - \theme\Theme
	private $theme;


	/**
	 * Function __construct
	 */
	function __construct() {

		$this->theme = Theme::instance();

		add_filter( 'pre_get_posts', array($this, 'search_posts_per_page') );
		add_filter( 'frontpage_template',  array($this, 'frontpage_template') );
		add_filter( 'email', array($this, 'email_filter'), 10, 5 );


		Theme::register( "Functions", $this );
		
	}



	/**
	 * Conditional helper to check shop existence
	 *
	 * @access public
	 * @static
	 *
	 * @param null|string $name
	 * @return string void|null
	 */
	public static function has_shop( $name = null ) {
		if ( $name && Theme::isset( "shop" ) )
			return ( Theme::get( "shop" ) === $name );

		return Theme::context( "shop" );
	}

	/**
	 * Conditional helper for the login page
	 *
	 *
	 * @access public
	 * @static
	 *
	 * @global null|string $pagenow
	 * @return bool
	 */
	public static function is_login() {
		global $pagenow;

		if ( $pagenow === 'wp-login.php' )
			return true;

		return false;
	}


	/**
	 * Conditional helper for AJAX mode
	 *
	 * @access public
	 * @static
	 *
	 * @return bool
	 */
	public static function is_ajax_mode() {
		if ( defined( 'AJAX' ) )
			return (bool) AJAX;

		if ( defined( 'DOING_AJAX' ) || (
			! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) &&
			strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest'
		) ) {
			define( 'AJAX', true );
			return true;
		}

		return false;
	}


	/**
	 * A filter for e-mail with encryption
	 *
	 * @access public
	 * @static
	 *
	 * @param string $content
	 * @param string $title
	 * @param string $class
	 * @param boolean $crypt
	 * @return string $r
	 */
	public static function email_filter( $content, $title = '', $class = '', $text = '', $crypt = true ) {
		if ( empty( $content ) )
			return;

		$content = esc_attr( $content );
		$email = $content;

		$mailto = '';

		if ( $crypt )  {
			$email = '';

			for ( $i = 0; $i < strlen( $content ); $i++ ) {
				$email .= "&#" . ord( $content[$i] ) . ";";
			}
		}

		for ( $i = 0; $i < strlen( 'mailto' ); $i++ ) {
			$m = 'mailto';
			$mailto .= "&#" . ord( $m[$i] ) . ";";
		}
		unset( $m );

		$r = "<a";

		if ( '' != $class )
			$r .= ' class="' . esc_attr( $class ) . '"';

		if ( '' != $mailto )
			$r .= ' href="' . esc_attr( $mailto . ':' . $email ) . '"';

		if ( '' != $title )
			$r .= ' title="' . esc_attr( $title ) . '"';

		$r .= ">";

		if ( $text )
			$r .= sanitize_text_field( $text );
		else
			$r .= $email;

		$r .= "</a>";

		return $r;
	}


	/**
	 * Helper to temporary disables image responsive 
	 *
	 * @see wp_calculate_image_srcset()
	 *
	 * @param array|null $image_meta
	 * @return array|null $image_meta
	 */
	public function image_disable_responsive( $image_meta ) {
		$image_meta['sizes'] = null;

		return $image_meta;
	}


	/**
	 * Limits the search post results for query
	 *
	 * @see /WP_Query->get_posts()
	 *
	 * @global array $wp_post_types
	 * @param object $query
	 * @return object $query
	 */
	public function search_posts_per_page( $query ) {
		if ( $query->is_search ) {
			global $wp_post_types;

			$wp_post_types['page']->exclude_from_search = true;

			$query->set( 'posts_per_page', get_option( 'posts_per_page' ) );
		}

		return $query;
	}


	/**
	 * Shows the selected page template in the front page
	 *
	 * @see get_front_page_template()
	 * @see get_home_template()
	 * @see get_query_template()
	 *
	 * @param string $template
	 * @return string void|$template
	 */
	public function frontpage_template( $template ) {
		if ( get_option( 'show_on_front', true ) )
			return get_home_template();

		if ( get_page_template_slug() )
			return get_page_template();

		return $template;
	}


}

new Functions;