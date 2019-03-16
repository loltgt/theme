<?php
/**
 * theme functions
 *
 * @package theme
 * @version 1.0
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

		add_action( 'wp', array($this, 'set_page_mode') );

		add_action( 'wp_ajax_send_form', array($this, 'send_form') );
		add_action( 'wp_ajax_nopriv_send_form', array($this, 'send_form') );
		add_action( 'wp', array($this, 'send_form_helper'), 20 );
		add_action( 'wp', array($this, 'send_form_wizard_helper'), 10 );
		add_action( 'admin_init', array($this, 'send_form_wizard_helper') );

		add_filter( 'pre_get_posts', array($this, 'search_posts_per_page') );
		add_filter( 'frontpage_template',  array($this, 'custom_frontpage_template_workaround') );
		add_filter( 'theme_mod_custom_logo', array($this, 'custom_brand_name_theme_mod_wrapper') );
		add_filter( 'email', array($this, 'email_filter'), 10, 5 );

		//TODO form field after and before
		//TODO form input field, number default value
		//TODO form input field, numeric range
		add_filter( 'theme_send_form_fields', array($this, 'send_form_default_fields'), 10, 5 );
		add_filter( 'theme_send_form_headers', array($this, 'send_form_multipart_headers'), 100 );
		add_filter( 'theme_send_form_body', array($this, 'send_form_timestamp_body'), 50 );
		add_filter( 'theme_send_form_body', array($this, 'send_form_multipart_body'), 100 );
		add_filter( 'theme_send_form_default_msgs', array($this, 'send_form_default_msgs') );

		Theme::register( "Functions", $this );
		
	}


	/**
	 * Sets page mode for layout
	 *
	 * @access public
	 * @static
	 *
	 * @return void bool
	 */
	public static function set_page_mode() {
		return Theme::set( "page:mode", Layer::get_field( 'page_mode' ) );
	}

	/**
	 * Gets page mode for layout
	 *
	 * @access public
	 * @static
	 *
	 * @return void string|null
	 */
	public static function get_page_mode() {
		return Theme::get( "page:mode" );
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
	 * Conditional helper for page mode: Modal
	 *
	 * @see \theme\Functions::get_page_mode()
	 *
	 * @access public
	 * @static
	 *
	 * @return void void|null
	 */
	public static function is_modal() {
		return Functions::get_page_mode() === 'modal' || false;
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
	 * text2html function to transform plain text to html format
	 *
	 * @access public
	 * @static
	 *
	 * @param string $text
	 * @param bool $strict
	 * @return string $html
	 */
	public static function text2html( $text, $strict = false ) {
		$elements = array( 'strong', 'em', 's', 'hr' );

		if ( $strict )
			$elements = array( 'b', 'i', 's', 'hr /' );

		$html = preg_replace(
			array(
				'/\s\{\s(.*?)\s\}\s/',
				'/\s\*(.*?)\*\s/',
				'/\s\/(.*?)\/\s/',
				'/\s_(.*?)_\s/',
				'/\s\*\s+(.*)?/',
				'/\s\d\.\s+(.*)?/',
				'/---/'
			),
			array(
				' <p>$1</p> ',
				' <' . $elements[0] . '>$1</' . $elements[0] . '> ',
				' <' . $elements[1] . '>$1</' . $elements[1] . '> ',
				' <' . $elements[2] . '>$1</' . $elements[2] . '> ',
				'<ul><li>$1</li></ul>',
				'<ol><li>$1</li></ol>',
				'<' . $elements[3] . '>'
			),
			$text
		);

		$patterns = array();

		if ( false !== strpos( $html, '<ul>' ) )
			$patterns[] = "/(\<\/ul\>\n(.*)\<ul\>*)+/";

		if ( false !== strpos( $html, '<ol>' ) )
			$patterns[] = "/(\<\/ol\>\n(.*)\<ol\>*)+/";

		if ( count( $patterns ) )
			$html = preg_replace( $patterns, array('', ''), $html );

		return $html;
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
	 * Generates a boundary string for mail submission
	 *
	 * @access public
	 * @static
	 *
	 * @return string void
	 */
	public static function generate_mail_boundary() {
		return sha1( '<php-mail-' . md5( microtime() ) . mt_rand() . '@git.php.net>' );
	}


	/**
	 * Helper to temporary disables image responsive 
	 *
	 * @see wp_calculate_image_srcset()
	 *
	 * @access public
	 * @static
	 *
	 * @param array|null $image_meta
	 * @return array|null $image_meta
	 */
	public static function image_disable_responsive( $image_meta ) {
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
	 * Workaround to display the selected page template in the front page
	 *
	 * //TODO review
	 *
	 * @see get_front_page_template()
	 * @see get_query_template()
	 *
	 * @param string $template
	 * @return string void|$template
	 */
	public function custom_frontpage_template_workaround( $template ) {
		if ( get_page_template_slug() )
			return get_page_template();

		return $template;
	}


	/**
	 * Wrapping around the ‘custom_logo‘ theme filter, 
	 * to return custom brand name instead of the default site name
	 *
	 * @see get_theme_mod()
	 * @see bloginfo()
	 *
	 * @param mixed $default
	 */
	public function custom_brand_name_theme_mod_wrapper( $default ) {
		add_filter( 'bloginfo', array($this, 'custom_brand_name'), 10, 2 );

		return $default;
	}


	/**
	 * Filters bloginfo name to return a custom brand name
	 *
	 * @see \theme\Customizer
	 * @see \theme\Options
	 * @see bloginfo()
	 *
	 * @param mixed $output
	 * @param mixed $show
	 * @return string $output
	 */
	public function custom_brand_name( $output, $show ) {
		if ( $show === 'name' )
			$output = $this->theme->Options->get_value( 'brand_name' );

		return $output;
	}


	/**
	 * Default routines of form with submission
	 */
	public function send_form_helper() {
		if ( isset( $_REQUEST['form-id'] ) || isset( $_GET['response'] ) ) {
			nocache_headers();

			if ( ! isset( $_COOKIE['form'] ) )
				setcookie( 'form', '', 0, '/' );
		}

		if ( isset( $_REQUEST['form-id'] ) )
			$this->send_form();

		/**
		 * theme_send_form_helper hook.
		 */
		do_action( 'theme_send_form_helper' );
	}


	/**
	 * Routines of wizard form
	 *
	 * @return void
	 */
	public function send_form_wizard_helper() {
		if ( defined( 'DOING_AJAX' ) && isset( $_REQUEST['form-id'] ) ) {
			$form_id = base64_decode( $_REQUEST['form-id'] );

			if ( false !== strpos( $form_id, '.' ) ) {
				$index = explode( '.', $form_id );
				$form_id = intval( $index[0] ) / 365;
			} else {
				$form_id = intval( $form_id );
			}

			if ( 'page-templates/wizard.php' !== get_page_template_slug( $form_id ) )
				return;
		} else if ( ! is_page_template( 'page-templates/wizard.php' ) ) {
			return;
		}

		if ( isset( $_REQUEST['form-id'] ) ) {
			if ( ! isset( $_COOKIE['wizard'] ) )
				setcookie( 'wizard', '', 0, '/' );
		} else if ( ! Layer::get_field( 'enable_pagination' ) ) {
			if ( $page = get_query_var( 'page' ) )
				$page = intval( $page );
			else
				$page = 1;
		}

		/**
		 * theme_send_form_helper hook.
		 */
		do_action( 'theme_send_form_wizard_helper' );

		remove_filter( 'theme_send_form_fields', array($this, 'send_form_default_fields'), 10, 5 );
		add_filter( 'theme_send_form_fields', array($this, 'send_form_wizard_fields'), 10, 3 );
		add_filter( 'theme_send_form_body', array($this, 'send_form_wizard_body'), 10, 4 );
		add_filter( 'theme_send_form_bypass', array($this, 'send_form_wizard_bypass'), 10, 4 );
	}


	/**
	 * Form handler, process the form submission and sends a message
	 *
	 * //TODO validation patterns
	 *
	 * @param int|bool $id
	 * @return void|array
	 */
	public function send_form( $id = false ) {
		if ( empty( $_REQUEST['form-id'] ) || empty( $_REQUEST['form-ref'] ) || empty( $_REQUEST['row'] ) )
			return $this->send_form_response();

		if ( ! check_ajax_referer( 'send-form', '_nonce', false ) )
			return $this->send_form_response();

		$form_id = base64_decode( $_REQUEST['form-id'] );
		$form_ref = base64_decode( $_REQUEST['form-ref'] );

		if (
			false == strpos( $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] ) ||
			false == strpos( $form_ref, $_SERVER['HTTP_HOST'] )
		) {
			return $this->send_form_response();
		}

		$form_ref = parse_url( $form_ref );
		$form_uri = parse_url( $_SERVER['REQUEST_URI'] );

		/**
		 * workaround: to set cookie(s) immediately
		 */
		$_COOKIE['form']['referer'] = json_encode( $form_ref );
		setcookie( 'form[referer]', $_COOKIE['form']['referer'], 0, '/' );

		$_COOKIE['form']['uri'] = json_encode( $form_uri );
		setcookie( 'form[uri]', $_COOKIE['form']['uri'], 0, '/' );

		$index = -1;
		$form = null;

		if ( ! $id ) {
			if ( false !== strpos( $form_id, '.' ) ) {
				$index = explode( '.', $form_id );
				$id = intval( $index[0] ) / 365;
				$index = intval( $index[1] );
			} else {
				$id = intval( $form_id );
			}
		}

		$post = get_post( $id );

		if ( ! $post )
			return $this->send_form_response();

		$fields = Layer::get_fields( $post );

		wp_reset_postdata();

		/**
		 * workaround: to set cookie(s) immediately
		 */
		$_COOKIE['form']['id'] = $form_id;
		setcookie( 'form[id]', $_COOKIE['form']['id'], 0, '/' );

		/**
		 * Filters the form fields
		 *
		 * @param array $form
		 * @param array $fields
		 * @param string $form_id
		 * @param int $id
		 * @param int $index
		 */
		$form = apply_filters( 'theme_send_form_fields', $form, $fields, $form_id, $id, $index );

		if ( empty( $form['fieldset'] ) )
			return $this->send_form_response();

		$request = $_REQUEST['row'];
		$method = 'post';

		if ( $form['enable_custom'] ) {
			if ( $form['form_attrs'] ) {
				if ( is_array( $form['form_attrs'] ) && array_key_exists( 'method', $form['form_attrs'] ) )
					$method = $attrs['method'];
			}
		}

		if (
			( $method === 'post' && empty( $_POST['row'] ) ) ||
			( $method === 'get' && empty( $_GET['row'] ) ) ||
			empty( $request )
		) {
			return $this->send_form_response();
		}

		/**
		 * Filters the sender address
		 *
		 * @param string $address
		 * @param string $form_id
		 * @param int $id
		 * @param int $index
		 */
		$address = apply_filters(
			'theme_send_form_address',
			get_bloginfo( 'admin_email' ),
			$form_id,
			$form,
			$request
		);

		/**
		 * Filters the mail headers
		 *
		 * @param string $header
		 * @param string $form_id
		 * @param int $id
		 * @param int $index
		 */
		$headers = apply_filters(
			'theme_send_form_headers',
			'',
			$form_id,
			$form,
			$request
		);

		/**
		 * Filters the mail subject
		 *
		 * @param string $subject
		 * @param string $form_id
		 * @param int $id
		 * @param int $index
		 */
		$subject = apply_filters( 'theme_send_form_subject',
			__( 'You have a message', 'theme' ),
			$form_id,
			$form,
			$request
		);

		$body = "\n\n";

		$errors = array();

		foreach ( $form['fieldset'] as $fieldset_i => $fieldset ) {
			foreach ( $fieldset['row'] as $i => $row ) {
				$field = $row['acf_fc_layout'];
				$label = isset( $row['label'] ) ? $row['label'] : '';
				$type = isset( $row['type'] ) ? $row['type'] : null;
				$validate = isset( $row['validate'] ) ? $row['validate'] : null;
				$required = isset( $row['required'] ) ? $row['required'] : null;
				$value = isset( $request[$fieldset_i][$i] ) ? $request[$fieldset_i][$i] : null;
				$emsg = isset( $row['emsg'] ) ? $row['emsg'] : null;

				switch ( $field ) {
					case 'input' :
						if ( $required && empty( $value ) ) {
							$errors["{$fieldset_i}:{$i}:{$field}:{$type}"] = array($label, $emsg);
						} else if ( $validate ) {
							if ( isset( $row['pattern'] ) ) {

							} else if ( $type == 'email' ) {
								if (! filter_var( $value, FILTER_VALIDATE_EMAIL ) )
									$errors["{$fieldset_i}:{$i}:{$field}:{$type}"] = array($label, $emsg);
							}
						}

						$value = sanitize_text_field( $value );

						//TODO review
						//if ( $type == 'email' || $type == 'url' )
						//	$value = "<{$value}>";

						$body .= " *{$label}* : \n{$value}\r\n\r\n";
					break;

					case 'textarea' :
						if ( $required && empty( $value ) ) {
							$errors["{$fieldset_i}:{$i}:{$field}:{$type}"] = array($label, $emsg);
						} else if ( $validate ) {
							if ( isset( $row['pattern'] ) ) {
							}
						}

						$value = sanitize_textarea_field( $value );

						$body .= " *{$label}* : \n\n{$value}\r\n\r\n";
					break;

					case 'select' :
						if ( $required && empty( $value ) )
							$errors["{$fieldset_i}:{$i}:{$field}:{$type}"] = array($label, $emsg);

						$body .= " *{$label}* : \n\n";

						foreach ( $row['options'] as $option ) {
							$body .= " * " . esc_html( $option['value'] );
							$body .= ( $option['value'] === $value ? " <== " : "" ) . "\n";
						}

						$body .= "\r\n";
					break;

					case 'checkbox' :
						if ( $required && empty( $value ) )
							$errors["{$fieldset_i}:{$i}:{$field}:{$type}"] = array($label, $emsg);

						if ( $value === 'on' )
							$value = _x( 'YES', 'checkbox-value', 'theme' );
						else
							$value = _x( 'NO', 'checkbox-value', 'theme' );

						$body .= " *{$label}* : \n\n" . $value . "\n\r\n";
					break;

					case 'hidden' :
						if ( apply_filters( 'theme_send_form_field_hidden', false ) ) {
							$value = esc_attr( $value );
							$body .= "[{$value}] \r\n";
						}
					break;
				}
			}
		}

		$body .= "\r\n";

		/**
		 * Filters the errors if obtained after the process of submitted form
		 *
		 * @param array $errors
		 * @param string $form_id
		 * @param array $form
		 * @param array $request
		 */
		$errors = apply_filters(
			'theme_send_form_errors',
			$errors,
			$form_id,
			$form,
			$request
		);

		/**
		 * Filters the mail body
		 *
		 * @param string $body
		 * @param string $form_id
		 * @param array $form
		 * @param array $fields
		 */
		$body = apply_filters(
			'theme_send_form_body',
			$body,
			$form_id,
			$form,
			$fields
		);

		if ( count( $errors ) )
			return $this->send_form_response( false, $errors );

		/**
		 * Filter to bypass mail send
		 *
		 * @param bool void
		 * @param string $form_id
		 * @param array $form
		 * @param array $fields
		 * @param string $address
		 * @param string $subject
		 * @param string $body
		 * @param string $headers
		 */
		if ( apply_filters(
			'theme_send_form_bypass',
			false,
			$form_id,
			$form,
			$fields,
			$address,
			$subject,
			$body,
			$headers
		) ) {
			return;
		}

		if ( wp_mail( $address, $subject, $body, $headers, "-f {$address}" ) )
			$this->send_form_response( true );
		else
			$this->send_form_response( false );
	}


	/**
	 * Handles the response after the process of form submission
	 *
	 * @param null|bool $status
	 * @param array $errors
	 * @return array $form
	 */
	public function send_form_response( $status = null, $errors = null ) {
		$response = array();

		/**
		 * Filter with default form messages
		 *
		 * @param array void
		 */
		$defaults = apply_filters( 'theme_send_form_default_msgs', array(
			'warning' => '',
			'success' => '',
			'errors' => '',
			'error' => '%s'
		) );

		if ( null === $status ) {
			$status = 'warning';
			$response['warning'][] = $defaults['warning'];
		} else if ( true === $status ) {
			$status = 'success';
			$response['success'][] = $defaults['success'];
		} else if ( $errors && count( $errors ) ) {
			$status = 'error';

			foreach ( $errors as $id => $error )
				$response['error'][$id] = sprintf( ( $error[1] ? $error[1] : $defaults['error'] ), $error[0] );
		} else {
			$status = 'error';
			$response['error'][] = $defaults['errors'];
		}

		if ( defined( 'DOING_AJAX' ) ) {
			header( 'Content-Type: application/json' );
			print( json_encode( array('status' => $status, 'response' => $response[$status]) ) );
			die( 0 );
		} else {
			Theme::set( "form-response", $response );

			if ( isset( $_COOKIE['form'] ) ) {
				$_form_ref = json_decode( stripslashes( $_COOKIE['form']['referer'] ), true );
				$_form_uri = json_decode( stripslashes( $_COOKIE['form']['uri'] ), true );

				foreach ( $_form_ref as $key => $value ) {
					$key = sanitize_key( $key );
					$value = array_map( 'sanitize_text_field', (array) $value );
					$form_ref = array( $key => $value );
				}

				foreach ( $_form_uri as $key => $value ) {
					$key = sanitize_key( $key );
					$value = array_map( 'sanitize_text_field', (array) $value );
					$form_uri = array( $key => $value );
				}

				$form_ref_todiff = trailingslashit( $form_ref['path'][0] ) .
					( isset( $form_ref['query'] ) ? '?' . urldecode( $form_ref['query'] ) : '' );
				$form_uri_todiff = trailingslashit( $form_uri['path'][0] ) .
					( isset( $form_uri['query'] ) ? '?' . urldecode( $form_uri['query'] ) : '' );

				$response_url = $form_ref;

				/**
				 * workaround: to set cookie(s) immediately
				 */
				$_COOKIE['form']['response'] = json_encode( $response );
				setcookie( 'form[response]', $_COOKIE['form']['response'], 0, '/' );

				if ( $status !== null && $form_ref_todiff !== $form_uri_todiff ) {
					if ( $status !== 'error' && dirname( $form_ref['path'][0] ) === dirname( $form_uri['path'][0] ) )
						$response_url = $form_uri;
				}

				if ( isset( $response_url['query'] ) ) {
					parse_str( $response_url['query'], $response_url['query'] );

					if ( array_key_exists( 'response', $response_url['query'] ) )
						unset( $response_url['query']['response'] );
				} else {
					$response_url['query'] = array();
				}

				$response_url['query']['response'] = $status;

				$response_url = trailingslashit( $response_url['path'][0] ) .
					( isset( $response_url['query'] ) ? '?' . http_build_query( $response_url['query'] ) : '' );

				wp_safe_redirect( $response_url );

				exit;
			}

			return $response;
		}
	}


	/**
	 * Transforms plain text mail based to html format
	 *
	 * @see \theme\Functions->text2html()
	 *
	 * @param string $text
	 * @return string $html
	 */
	public function send_form_text2html( $text ) {
		$body = $this->text2html( $text, true );

		/**
		 * Default styles of html mail body
		 *
		 * @param string $style
		 */
		$style = apply_filters(
			'theme_send_form_multipart_default_style',
			'font-size: 16px; font-family: "Helvetica", "Arial", sans-serif; line-height: 20px;'
		);

		$body = str_replace(
			array("<p>", "<b>", ": \n", "\r\n\r\n"),
			array("<p style=\"{$style}\">", "<p style=\"{$style}\"><b>", ":<br />\n", "</p>\r\n\r\n"),
			$body
		);

		/**
		 * Overrides the default html mail template
		 *
		 * @param string $html
		 */
		if ( $html = apply_filters( 'theme_send_form_multipart_html_template', false ) ) {
			$html = str_replace( array('{{DEFAULT_STYLE}}', '{{BODY}}'), array($style, $body), $html );
		} else {
			ob_start();

			get_template_part( 'template-parts/mail-multipart-html-default' );

			$html = str_replace( array('{{DEFAULT_STYLE}}', '{{BODY}}'), array($style, $body), ob_get_clean() );
		}

		return $html;
	}


	/**
	 * Adds a time signature at the end of the mail body
	 *
	 * @see \theme\Functions->send_form()
	 *
	 * @param string $body
	 * @return string $body
	 */
	public function send_form_timestamp_body( $body ) {
		$body .= "---\r\n\n";
		$body .= ' { ' . sprintf(
			__( 'Sent from the server %s', 'theme' ),
			date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) )
		) . ' } ';
		$body .= "\n\r\n";

		return $body;
	}


	/**
	 * Adds multipart MIME message headers to the mail
	 *
	 * @see \theme\Functions->send_form()
	 *
	 * @param string $headers
	 * @return string $headers
	 */
	public function send_form_multipart_headers( $headers ) {
		( ! Theme::isset( "form-boundary" ) ) && Theme::set( "form-boundary", $this->generate_mail_boundary() );

		$boundary = Theme::get( "form-boundary" );

		$headers .= "Content-Type: multipart/alternative; charset=UTF-8; boundary={$boundary}\r\n";
		$headers .= "Content-Transfer-Encoding: quoted-printable\r\n";

		return $headers;
	}


	/**
	 * Adapts the mail body to reflect multipart MIME specs
	 *
	 * @see \theme\Functions->send_form()
	 *
	 * @param string $body
	 * @return string $body
	 */
	public function send_form_multipart_body( $body ) {
		( ! Theme::isset( "form-boundary" ) ) && Theme::set( "form-boundary", $this->generate_mail_boundary() );

		$text = $body;
		$html = $this->send_form_text2html( $body );
		$boundary = Theme::get( "form-boundary" );

		$body = "This is a MIME encoded message.";
		$body .= "\r\n\r\n--{$boundary}\r\n";
		$body .= "Content-Type: text/plain; charset=UTF-8\r\n";
		$body .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
		$body .= quoted_printable_encode( chunk_split( $text ) );
		$body .= "\r\n\r\n--{$boundary}\r\n";
		$body .= "Content-Type: text/html; charset=UTF-8\r\n";
		$body .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
		$body .= quoted_printable_encode( chunk_split( $html ) );
		$body .= "\r\n\r\n--{$boundary}--";

		return $body;
	}


	/**
	 * Default filter for form fields in single and nested pages
	 *
	 * @see \theme\Functions->send_form()
	 *
	 * @param array $form
	 * @param array $fields
	 * @param string $form_id
	 * @param int $id
	 * @param int $index
	 * @return array $form
	 */
	public function send_form_default_fields( $form, $fields, $form_id, $id, $index ) {
		if ( -1 !== $index && ! empty( $fields['layers'] ) ) {
			$sections = array_column( $fields['layers'], 'section' );

			$i = 0;

			foreach ( $sections as $section ) {
				foreach ( $section as $row ) {
					//TODO Layer-ize
					if ( $row['acf_fc_layout'] === 'form' ) {
						if ($i === $index)
							$form = $row;
						else
							$i++;
					}
				}
			}
		} else if ( $fields ) {
			$form = $fields;
		}

		return $form;
	}


	/**
	 * Default form messages
	 *
	 * @see \theme\Functions->send_form_response()
	 *
	 * @return array
	 */
	public function send_form_default_msgs() {
		return array(
			'warning' => __( 'Bad Request! An error is occurred with malformed request.', 'theme' ),
			'success' => __( 'Your message was sent successfully!', 'theme' ),
			'errors' => __( 'Sorry! There were problems during send, please try again later.', 'theme' ),
			'error' => __( '%s is a required field.', 'theme' )
		);
	}


	/**
	 * Default filter for wizard form fields
	 *
	 * @see \theme\Functions->send_form()
	 *
	 * @param array $form
	 * @param array $fields
	 * @param string $form_id
	 * @return array $form
	 */
	public function send_form_wizard_fields( $form, $fields, $form_id ) {
		if ( ! isset( $_REQUEST['wizard'] ) || empty( $fields['pages'] ) )
			return false;

		$page = intval( $_REQUEST['wizard'] );

		$row = 0;
		$total = ceil( count( $fields['pages'] ) / 1 );
		$min = ( ( $page * 1 ) - 1 ) + 1;
		$max = ( $min + 1 ) - 1;

		foreach ( $fields['pages'] as $page ) {
			$row++;

			if ( $row < $min ) 
				continue;

			if ( $row > $max )
				break;

			$form = $page;
		}

		return $form;
	}


	/**
	 * Formats the mail body for wizard forms
	 *
	 * @see \theme\Functions->send_form()
	 *
	 * @param body $body
	 * @param string $form_id
	 * @param array $form
	 * @param array $fields
	 * @return string $body|void
	 */
	public function send_form_wizard_body( $body, $form_id, $form, $fields ) {
		if ( empty( $fields['pages'] ) || empty( $_REQUEST['wizard'] ) )
			return '';

		if ( $page = get_query_var( 'page' ) )
			$page = intval( $page );
		else
			$page = 1;

		$i = intval( $_REQUEST['wizard'] );
		$end = count( $fields['pages'] );

		/**
		 * workaround: to set cookie(s) immediately
		 */
		if ( empty( $_COOKIE['wizard'] ) )
			$_COOKIE['wizard'] = array();

		$_COOKIE['wizard'][$i] = base64_encode( $body );
		setcookie( "wizard[$i]", $_COOKIE['wizard'][$i], 0, '/' );

		if ( $i === $end || $form['submission_page'] ) {
			$body = '';

			foreach ( $_COOKIE['wizard'] as $index => $value ) {
				$body .= base64_decode( $value );

				if ( $index !== $end )
					$body .= "\n\r\n *" . sprintf( _x( 'Page %s', 'wizard', 'theme' ), $index ) . "* \r\n\r\n";
			}

			return sanitize_textarea_field( $body );
		}

		return '';
	}


	/**
	 * To stop or not mail send for wizard forms, 
	 * it sends only at the end of the wizard or if the page has submit flag
	 *
	 * @see \theme\Functions->send_form()
	 *
	 * @param bool void
	 * @param string $form_id
	 * @param array $form
	 * @param array $fields
	 * @return bool void
	 */
	public function send_form_wizard_bypass( $return, $form_id, $form, $fields ) {
		if ( empty( $fields['pages'] ) || empty( $_REQUEST['wizard'] ) )
			return $this->send_form_response();

		$i = intval( $_REQUEST['wizard'] );
		$end = count( $fields['pages'] );

		if ( $i === $end || $form['submission_page'] ) {
			if ( $i !== count( $_COOKIE['wizard'] ) )
				return $this->send_form_response();

			return false;
		}

		if ( defined( 'DOING_AJAX' ) ) {
			remove_filter( 'theme_send_form_default_msgs', array($this, 'send_form_default_msgs') );

			return $this->send_form_response( true );
		}

		return true;
	}


}

new Functions;