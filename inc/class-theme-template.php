<?php
/**
 * theme templating functions
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Theme;
use \theme\Options;
use \theme\Functions;
use \theme\Layer;


/**
 * Templating class
 */
class Template {

	// @type object $theme - \theme\Theme
	private $theme;


	/**
	 * Function __construct
	 */
	function __construct() {

		$this->theme = Theme::instance();

		add_action( 'wp', array($this, 'page_mode_filters') );

		add_action( 'theme_send_form_helper', array($this, 'send_form_actions') );
		add_action( 'theme_send_form_wizard_helper', array($this, 'send_form_wizard_actions') );

		add_action( 'theme_modal_header_end', array($this, 'modal_header_close_button') );
		add_action( 'theme_page_layer_loop_start', array($this, 'page_layer_loop_start') );
		add_action( 'theme_page_layer_loop_end', array($this, 'page_layer_loop_end') );
		add_action( 'theme_page_block_loop_start', array($this, 'page_block_loop_start') );
		add_action( 'theme_page_block_loop_end', array($this, 'page_block_loop_end') );
		add_action( 'theme_page_layer_content_start', array($this, 'page_layer_content_start') );
		add_action( 'theme_page_layer_content_end', array($this, 'page_layer_content_end') );
		add_action( 'theme_before_form', array($this, 'before_form') );
		add_action( 'theme_before_slideshow', array($this, 'before_slideshow') );
		add_action( 'theme_after_form', array($this, 'after_form') );

		add_filter( 'body_class', array($this, 'body_class'), 10, 3 );
		add_filter( 'post_class', array($this, 'child_page_post_class'), 10, 3 );
		add_filter( 'the_content', array($this, 'format_blockquote') );
		add_filter( 'excerpt_more', array($this, 'excerpt_more') );
		add_filter( 'wp_get_attachment_link', array($this, 'attachment_link_class') );
		add_filter( 'post_thumbnail_html', array($this, 'post_thumbnail_html_sizes'), 10, 4 );
		add_filter( 'get_calendar', array($this, 'get_calendar') );
		add_filter( 'show_recent_comments_widget_style', '__return_false' );
		add_filter( 'wp_generate_tag_cloud', array($this, 'remove_tag_cloud_inline_styles') );
		add_filter( 'get_custom_logo', array($this, 'custom_logo'), 9999 );

		add_filter( 'theme_modal_header', array($this, 'modal_header') );
		add_filter( 'theme_modal_body', array($this, 'modal_body') );
		add_filter( 'theme_modal_footer', array($this, 'modal_footer') );

		add_filter( 'theme_data_id_default', array($this, 'data_id_default') );
		add_filter( 'theme_data_id_gallery', array($this, 'data_id_gallery') );
		add_filter( 'theme_data_id_slideshow', array($this, 'data_id_slideshow') );
		add_filter( 'theme_data_id_form', array($this, 'data_id_form') );
		add_filter( 'theme_data_id_form_fieldset', array($this, 'data_id_form_fieldset') );
		add_filter( 'theme_data_id_form_field', array($this, 'data_id_form_field') );

		add_filter( 'theme_data_class_page_layer', array($this, 'data_class_page_layer') );
		add_filter( 'theme_data_class_page_block', array($this, 'data_class_page_block') );
		add_filter( 'theme_data_class_gallery', array($this, 'data_class_gallery') );
		add_filter( 'theme_data_class_gallery_item', array($this, 'data_class_gallery_item') );
		add_filter( 'theme_data_class_slideshow', array($this, 'data_class_slideshow') );
		add_filter( 'theme_data_class_form_row', array($this, 'data_class_form_row') );
		add_filter( 'theme_data_class_form_field_label', array($this, 'data_class_form_field_label') );

		add_filter( 'theme_data_extras_page', array($this, 'data_extras_page') );
		add_filter( 'theme_data_extras_gallery', array($this, 'data_extras_gallery') );
		add_filter( 'theme_data_extras_slideshow', array($this, 'data_extras_slideshow') );
		add_filter( 'theme_data_extras_slideshow_slide_video', array($this, 'data_extras_slideshow_slide_video') );
		add_filter( 'theme_data_extras_slideshow_slide_caption', array($this, 'data_extras_slideshow_slide_caption') );
		add_filter( 'theme_data_extras_form', array($this, 'data_extras_form') );
		add_filter( 'theme_data_extras_form_fieldset', array($this, 'data_extras_form_fieldset'), 10, 3 );
		add_filter( 'theme_data_extras_form_field_input', array($this, 'data_extras_form_field'), 10, 3 );
		add_filter( 'theme_data_extras_form_field_textarea', array($this, 'data_extras_form_field'), 10, 3 );
		add_filter( 'theme_data_extras_form_field_select', array($this, 'data_extras_form_field'), 10, 3 );
		add_filter( 'theme_data_extras_form_field_checkbox', array($this, 'data_extras_form_field'), 10, 3 );
		add_filter( 'theme_data_extras_form_field_button', array($this, 'data_extras_form_field'), 10, 3 );
		add_filter( 'theme_data_extras_form_field_button', array($this, 'data_extras_form_field_button'), 20, 3 );
		add_filter( 'theme_data_extras_form_field_hidden', array($this, 'data_extras_form_field'), 10, 3 );
		add_filter( 'theme_data_extras_form_field_select_option', array($this, 'data_extras_form_field_select_option'), 10, 4 );

		add_filter( 'theme_form_field_label', array($this->theme->Functions, 'text2html') );
		add_filter( 'theme_format_blockquote', array($this, 'format_blockquote') );
		add_filter( 'theme_legacy_colors', array($this, 'legacy_colors_inline_style') );

		add_filter( 'comment_form_defaults', array($this, 'comment_form_defaults') );
		add_filter( 'comment_form_fields', array($this, 'comment_form_fields') );
		add_filter( 'comment_reply_link', array($this, 'comment_reply_link') );
		add_filter( 'edit_comment_link', array($this, 'edit_comment_link') );
		add_filter( 'cancel_reply_link', array($this, 'cancel_reply_link') );

		add_filter( 'wp_link_pages_args', array($this, 'navigation_link_pages_args') );
		add_filter( 'wp_link_pages_link', array($this, 'navigation_link_pages_link') );
		add_filter( 'navigation_markup_template', array($this, 'navigation_markup_template') );
		add_filter( 'previous_comments_link_attributes', array($this, 'navigation_link_prev_class') );
		add_filter( 'next_comments_link_attributes', array($this, 'navigation_link_next_class') );
		add_filter( 'previous_posts_link_attributes', array($this, 'navigation_link_prev_class') );
		add_filter( 'next_posts_link_attributes', array($this, 'navigation_link_next_class') );
		add_filter( 'wp_nav_menu_items', array( $this, 'remove_itemscope_nav_el' ) );

		if ( ! $this->theme->Setup->wp5 ) {
			add_filter( 'get_image_tag', array($this, 'get_image_tag'), 10, 4 );
			add_filter( 'image_send_to_editor', array($this, 'image_send_to_editor'), 10, 3 );
		}

		if ( $this->theme->Options->get_value( 'unexpone_ID', false ) ) {
			add_filter( 'body_class', array($this, 'unexpone_id_body_class') );
			add_filter( 'post_class', array($this, 'unexpone_id_post_class'), 9999, 2 );
			add_filter( 'nav_menu_item_id', array($this, 'unexpone_id_nav_menu_item_id'), 10, 2 );
			add_filter( 'nav_menu_css_class', array($this, 'unexpone_id_nav_menu_item_class'), 10, 2 );
			remove_filter( 'theme_data_id_default', array($this, 'data_id_default') );
			add_filter( 'theme_data_id_default', array($this, 'unexpone_id_data_id_default') );
		}

		Theme::register( "Template", $this );

	}



	/**
	 * Filtering for page mode
	 */
	public function page_mode_filters() {
		$page_mode = $this->theme->Functions::get_page_mode();

		if ( ! $page_mode )
			return;

		if ( $page_mode === 'modal' && ! is_page_template( 'page-templates/modal.php' ) ) {
			add_filter( 'template_include', array($this, 'page_mode_modal_template_override') );
			add_filter( 'the_title', array($this, 'page_mode_modal_title') );
			add_filter( 'the_content', array($this, 'page_mode_modal_content') );
		}
	}


	/**
	 * Send form actions and notices
	 *
	 * @see \theme\Functions->send_form_helper()
	 */
	public function send_form_actions() {
		if ( did_action( 'theme_notices' ) )
			add_action( 'theme_print_notices', array($this, 'print_notices') );

		/**
		 * Fires theme notices
		 *
		 * theme_notices hook.
		 */
		do_action( 'theme_notices' );

		if ( ! isset( $_COOKIE['form']['response'] ) )
			return;

		$_response = json_decode( stripslashes( $_COOKIE['form']['response'] ), true );

		foreach ( $_response as $key => $value ) {
			$key = sanitize_key( $key );
			$value = array_map( 'sanitize_text_field', $value );

			Theme::set( "form-response", array( $key => $value ) );
		}

		/**
		 * workaround: to set cookie(s) immediately
		 */
		unset( $_COOKIE['form']['response'] );

		setcookie( 'form[response]', '', 0, '/' );
	}


	/**
	 * Send wizard form actions and notices
	 *
	 * @see \theme\Functions->send_form_wizard_helper()
	 */
	public function send_form_wizard_actions() {
		add_action( 'theme_after_form', array($this, 'wizard_after_form') );
		add_filter( 'theme_data_extras_form', array($this, 'data_extras_form_wizard') );

		if ( empty( $_COOKIE['form']['response'] ) && Theme::empty( "form-response" ) ) {
			Theme::set( "form-response", array( 'info' => array( __( 'JavaScript must be enabled to use this feature.' ) ) ) );
		}

		/**
		 * Fires theme notices
		 *
		 * theme_notices hook.
		 */
		do_action( 'theme_notices' );
	}


	/**
	 * Adds custom body classname(s)
	 *
	 * @see get_body_class()
	 *
	 * @param array $classes
	 * @return array $classes
	 */
	public function body_class( $classes ) {
		// Add class on front page
		if ( is_front_page() && 'posts' !== get_option( 'show_on_front' ) )
			$classes[1] = 'page-template-front-page';

		// Hero (legacy support)
		if ( has_page_hero() )
			$classes[] = 'has-hero';

		return $classes;
	}


	/**
	 * Adds a classname to the page post class
	 *
	 * @see get_post_class()
	 *
	 * @param array $classes
	 * @param array $class
	 * @param int $post_id
	 * @return array $classes
	 */
	public function child_page_post_class( $classes, $class, $post_id ) {
		$post = get_post( $post_id );

		if ( ! $post->post_parent )
			return $classes;

		if ( $template = get_page_template_slug() ) {
			$classes[] = 'page-child-template-' . sanitize_html_class(
				str_replace( array('.', '/'), '-', basename( $template, '.php' ) )
			);
		}

		$classes[] = 'page-child';

		return $classes;
	}


	/**
	 * Filters the content for blockquote
	 *
	 * @see the_content()
	 *
	 * @param string $content
	 * @return string $content
	 */
	public function format_blockquote( $content ) {
		if ( false === strpos( $content, '<blockquote>' ) )
			return $content;

		$content = str_replace(
			array(
				'<blockquote>',
				'<cite>',
				'</cite>',
				'</blockquote>'
			),
			array(
				'<blockquote class="blockquote">',
				'<footer class="blockquote-footer"><cite>',
				'</cite></footer>',
				'</blockquote>'
			),
			$content
		);

		return $content;
	}


	/**
	 * Custom excerpt ellipsis
	 *
	 * @see wp_trim_excerpt()
	 *
	 * @return string void
	 */
	public function excerpt_more() {
		return " <span class=\"ellipsis\">&hellip;</span>";
	}


	/**
	 * Output theme notices
	 */
	public function print_notices() {
		if ( ! Theme::isset( "form-response" ) ) return;

		foreach ( Theme::get( "form-response" ) as $type => $messages ) {
			set_query_var( 'response', array('type' => $type, 'messages' => $messages) );

			get_template_part( 'template-parts/notices', $type );
		}
	}


	/**
	 * Frontend custom logo
	 *
	 * @see get_custom_logo()
	 * @see bloginfo()
	 *
	 * @param string $html
	 */
	public function custom_logo( $html ) {
		if ( $html ) {
			$html = str_replace(
				array('custom-logo-link', 'custom-logo'),
				array('navbar-brand custom-logo-link', 'custom-logo site-logo'),
				$html
			);
		}

		remove_filter( 'bloginfo', array($this->theme->Functions, 'custom_brand_name'), 10, 2 );

		return $html;
	}


	/**
	 * The default ID attribute
	 *
	 * @see \theme\get_data_ID()
	 *
	 * @param string $context
	 * @return string void
	 */
	public function data_id_default( $context ) {
		return get_the_ID();
	}


	/**
	 * Data ID attribute for gallery
	 *
	 * @see \theme\get_data_ID()
	 *
	 * @return int void
	 */
	public function data_id_gallery() {
		$atts = get_query_var( 'shortcode_atts' );

		return intval( $atts['id'] );
	}


	/**
	 * Data ID attribute for slideshow
	 *
	 * @see \theme\get_data_ID()
	 *
	 * @param string $context
	 * @return string $id
	 */
	public function data_id_slideshow( $context ) {
		if ( $context === 'shortcode' ) {
			$atts = get_query_var( 'shortcode_atts' );
			$id = intval( $atts['id'] );
		} else {
			$i = 0;
			$id = apply_filters( 'theme_data_id_default', $context );

			if ( Theme::isset( "latest:slideshow" ) ) {
				if ( false !== strpos( Theme::get( "latest:slideshow" ), '-' ) ) {
					$i = explode( '-', Theme::get( "latest:slideshow" ) );
					$i = intval( $i[1] );
				} else {
					$i = intval( Theme::get( "latest:slideshow" ) );
				}
			}

			if ( Theme::isset( "current:slideshow" ) ) {
				if ( Theme::get( "current:slideshow" ) === -1 )
					$i++;
			}

			$id .= '-' . $i;
		}

		$selector = "slideshow_{$id}";

		Theme::set( "current:slideshow", $id );
		Theme::set( "latest:slideshow", $id );
		Theme::set( "latest:{$id}:slideshow", $selector );

		$id = "{$id}";

		return $id;
	}


	/**
	 * Data ID attribute for form
	 *
	 * @see \theme\get_data_ID()
	 *
	 * @param string $context
	 * @return string $id
	 */
	public function data_id_form( $context ) {
		if ( $context === 'shortcode' ) {
			$atts = get_query_var( 'shortcode_atts' );
			$id = intval( $atts['id'] );
		} else {
			$i = 0;

			if ( $context === 'expone' )
				$_id = get_the_ID();
			else
				$_id = apply_filters( 'theme_data_id_default', $context );

			if ( Theme::isset( "latest:form" ) ) {
				if ( false !== strpos( Theme::get( "latest:form" ), '-' ) ) {
					$i = explode( '-', Theme::get( "latest:form" ) );
					$i = intval( $i[1] );
				} else {
					$i = intval( Theme::get( "latest:form" ) );
				}
			}

			if ( Theme::isset( "current:form" ) ) {
				if ( Theme::get( "current:form" ) === -1 )
					$i++;
			}

			$id = $_id . '-' . $i;
		}

		Theme::set( "form-nonce", wp_create_nonce( 'send-form' ) );

		if ( Layer::get_field( 'enable_custom' ) )
			Theme::set( "form-custom", true );

		if ( Layer::get_field( 'enable_validation' ) )
			Theme::set( "form-validation", true );

		if ( Layer::get_field( 'enable_placeholders' ) )
			Theme::set( "form-placeholders", true );

		$selector = "form_{$id}";

		Theme::set( "current:form", $id );
		Theme::set( "latest:form", $id );
		Theme::set( "latest:{$id}:form", $selector );

		if ( $context === 'expone' )
			$id = intval( $_id ) * 365 . '.' . $i; 
		else
			$id = "{$id}";

		return $id;
	}


	/**
	 * Data ID attribute for form fieldset
	 *
	 * @see \theme\get_data_ID()
	 *
	 * @param string $context
	 * @return string $id
	 */
	public function data_id_form_fieldset( $context ) {
		$form_id = Theme::get( "latest:form" );

		$i = 0;

		if ( Theme::isset( "latest:{$form_id}:fieldset" ) ) {
			$i = Theme::get( "latest:{$form_id}:fieldset", 0 );
			$i = (int) $i + 1;
		}

		$selector = "fieldset_{$form_id}_{$i}";

		Theme::set( "latest:{$form_id}:fieldset", $i );
		Theme::set( "latest:{$form_id}:{$i}:fieldset", $selector );

		$id = "{$form_id}-{$i}";

		return $id;
	}


	/**
	 * Data ID attribute for form field
	 *
	 * @see \theme\get_data_ID()
	 *
	 * @param int|array $context
	 * @return string $id
	 */
	public function data_id_form_field( $context ) {
		$form_id = Theme::get( "latest:form" );
		$fieldset_i = Theme::get( "latest:{$form_id}:fieldset", 0 );

		$name = false;

		if ( is_numeric( $context ) ) {
			$i = $context;
		} else {
			$i = (int) $context[0];
			$name = true;
		}

		$selector = "row_{$form_id}_{$fieldset_i}_{$i}";

		Theme::set( "latest:{$form_id}:row", $i );
		Theme::set( "latest:{$form_id}:{$fieldset_i}:{$i}:row", $selector );

		$id = ( $name ? "[$fieldset_i][$i]" : "{$form_id}-{$fieldset_i}-{$i}" );

		return $id;
	}


	/**
	 * Data classname attribute for page layer
	 *
	 * @see \theme\get_data_class()
	 *
	 * @param array $classes
	 * @return array $classes
	 */
	public function data_class_page_layer( $classes ) {
		$view = null;
		$i = Theme::get( "latest:layer", 0 );

		$classes[] = "page-content-layer-{$i}";
		$classes[] = 'container';

		return $classes;
	}


	/**
	 * Data classname attribute for page block
	 *
	 * @see \theme\get_data_class()
	 *
	 * @param array $classes
	 * @return array $classes
	 */
	public function data_class_page_block( $classes ) {
		$layer_i = Theme::get( "latest:layer" );
		$block_i = Theme::get( "current:block" );
		$layout = Layer::get_field_name();
		$view = Theme::get( "layer-view" );

		if ( $layout )
			$classes[] = "block-template-{$layout}";

		$classes[] = "page-content-block-{$layer_i}-{$block_i}";

		$has_classes = false;

		if ( Theme::isset( "layer-custom" ) ) {
			if ( $_classes = Layer::get_subfield( 'classes' ) ) {
				$_classes = explode( ' ', $_classes );
				$classes = array_merge( $classes, $_classes );

				$has_classes = true;
			}
		}

		if ( ! $has_classes && $view === 'cols' ) {
			if ( $col = intval( Layer::get_subfield( 'col' ) ) )
				$classes[] = "col-lg-{$col}";
		}

		return $classes;
	}


	/**
	 * Data classname attribute for gallery
	 *
	 * @see \theme\get_data_class()
	 *
	 * @param array $classes
	 * @return array $classes
	 */
	public function data_class_gallery( $classes ) {
		$atts = get_query_var( 'shortcode_atts' );

		if ( ! $atts )
			return $classes;

		if ( $columns = $atts['columns'] )
			$classes[] = "gallery-columns-{$columns}";

		if ( $size = $atts['size'] )
			$classes[] = "gallery-size-{$size}";

		if ( $atts['columns'] )
			$classes[] = 'row';

		return $classes;
	}


	/**
	 * Data classname attribute for gallery item
	 *
	 * @see \theme\get_data_class()
	 *
	 * @param array $classes
	 * @return array $classes
	 */
	public function data_class_gallery_item( $classes ) {
		$atts = get_query_var( 'shortcode_atts' );

		if ( ! $atts )
			return $classes;

		if ( $cols = $atts['cols'] )
			$classes[] = "col-md-{$cols}";

		return $classes;
	}


	/**
	 * Data classname attribute for slideshow
	 *
	 * @see \theme\get_data_class()
	 *
	 * @param array $classes
	 * @return array $classes
	 */
	public function data_class_slideshow( $classes ) {
		if ( ! Layer::get_field( 'enable_slideshow_tpp' ) )
			$classes[] = 'owl-carousel';

		return $classes;
	}


	/**
	 * Data classname attribute for form row
	 *
	 * @see \theme\get_data_class()
	 *
	 * @param array $classes
	 * @return array $classes
	 */
	public function data_class_form_row( $classes ) {
		$type = Layer::get_field_name();
		$view = Theme::get( 'form-fieldset-view' );

		/**
		 * Filter to match custom controls of css framework
		 *
		 * @param bool void
		 */
		if ( apply_filters( 'theme_form_bs_custom', '__return_true' ) ) {
			if ( $type == 'checkbox' ) {
				$classes[0] = 'custom-control';
				$classes[1] = 'custom-checkbox';
			}
		} else {
			if ($type === 'checkbox')
				$classes[0] = 'form-check';
		}

		if ( $view === 'cols' ) {
			if ( $col = intval( Layer::get_subfield( 'col' ) ) )
				$classes[] = "col-lg-{$col}";
		}

		return $classes;
	}


	/**
	 * Data classname attribute for form field label
	 *
	 * @see \theme\get_data_class()
	 *
	 * @param array $classes
	 * @return array $classes
	 */
	public function data_class_form_field_label( $classes ) {
		$type = Layer::get_field_name();

		/**
		 * Filter for form placeholder
		 *
		 * @param bool void
		 */
		$field_placeholder = apply_filters(
			'theme_form_field_placeholder',
			Theme::isset( "form-placeholders" ) || Layer::get_subfield( 'placeholder' )
		);

		/**
		 * Filter to match custom controls of css framework
		 *
		 * @param bool void
		 */
		if ( apply_filters( 'theme_form_bs_custom', '__return_true' ) ) {
			if ( $type == 'checkbox' )
				$classes[0] = 'custom-control-label';
		}

		if ( $field_placeholder ) {
			if ($type != 'checkbox')
				$classes[] = 'sr-only';
		}

		return $classes;
	}


	/**
	 * Data extra attributes for page
	 *
	 * @see \theme\get_data_extras()
	 *
	 * @return array $params
	 */
	public function data_extras_page() {
		$params = array();

		if ( Layer::get_field( 'enable_page_appearance', null, true ) ) {
			$appearance = Layer::get_field( 'page_appearance', null, true );
		} else {
			return $params;
		}

		if ( $appearance['foreground'] )
			$params['data-foreground-color'] = esc_attr( maybe_hash_hex_color( $appearance['foreground'] ) );

		if ( $appearance['background'] )
			$params['data-background-color'] = esc_attr( maybe_hash_hex_color( $appearance['background'] ) ); 

		if ( $appearance['enable_shadow'] ) {
			if ( $appearance['shadow'] )
				$params['page-shadow-color'] = esc_attr( maybe_hash_hex_color( $appearance['shadow'] ) );
		}

		/**
		 * Filter to legacy transform data attributes in a style attribute
		 *
		 * @param array $params
		 */
		$params = apply_filters( 'theme_legacy_colors', $params );

		return $params;
	}


	/**
	 * Data extra attributes for gallery
	 *
	 * @see \theme\get_data_extras()
	 *
	 * @return array $params
	 */
	public function data_extras_gallery() {
		$params = array();

		if ( is_archive() )
			$atts['slider'] = $this->theme->Options->get_value( 'entry_gallery_slider', true );

		$atts = get_query_var( 'shortcode_atts' );

		if ( $atts['slider'] )
			$params['data-slider'] = '';

		return $params;
	}


	/**
	 * Data extra attributes for slideshow
	 *
	 * @see \theme\get_data_extras()
	 *
	 * @return array $params
	 */
	public function data_extras_slideshow() {
		$params = array();

		if ( $autoplay = Layer::get_field( 'enable_autoplay' ) ) {
			$params['data-autoplay'] = '';

			if ( $duration_time = Layer::get_field( 'autoplay_duration_time' ) )
				$params['data-autoplay-duration-time'] = (int) $duration_time;

			if ( $transition_time = Layer::get_field( 'autoplay_transition_time' ) )
				$params['data-autoplay-transition-time'] = (int) $transition_time;
		}

		return $params;
	}


	/**
	 * Data extra attributes for video slide
	 *
	 * @see \theme\get_data_extras()
	 * 
	 * @return array $params
	 */
	public function data_extras_slideshow_slide_video() {
		$params = array();

		if ( $loop = Layer::get_subfield( 'video_params_loop' ) )
			$params['loop'] = '';

		if ( $autoplay = Layer::get_subfield( 'video_params_autoplay' ) )
			$params['autoplay'] = '';

		if ( $poster = Layer::get_subfield( 'video_params_poster' ) )
			$params['poster'] = esc_attr( esc_url( $poster['url'] ) );

		return $params;
	}


	/**
	 * Data extra attributes for slide caption
	 *
	 * @see \theme\get_data_extras()
	 *
	 * @return array $params
	 */
	public function data_extras_slideshow_slide_caption() {
		$params = array();

		if ( $foreground = Layer::get_field( 'caption_foreground' ) )
			$params['data-foreground-color'] = esc_attr( maybe_hash_hex_color( $foreground ) );

		if ( Layer::get_field( 'caption_enable_background' ) ) {
			if ( $background = Layer::get_field( 'caption_background' ) )
				$params['data-background-color'] = esc_attr( maybe_hash_hex_color( $background ) ); 
		}

		if ( Layer::get_field( 'caption_enable_shadow' ) ) {
			if ( $shadow = Layer::get_field( 'caption_shadow' ) )
				$params['data-shadow-color'] = esc_attr( maybe_hash_hex_color( $shadow ) ); 
		}

		/**
		 * Filter to legacy transform data attributes in a style attribute
		 *
		 * @param array $params
		 */
		$params = apply_filters( 'theme_legacy_colors', $params );

		return $params;
	}


	/**
	 * Data extra attributes for form
	 *
	 * @see \theme\get_data_extras()
	 * 
	 * @return array $params
	 */
	public function data_extras_form() {
		/**
		 * Filter to enable/disable the browser built-in validation
		 *
		 * @param bool void
		 */
		$browser_validation = apply_filters( 'theme_form_browser_validation', false ) ? 'validate' : 'novalidate';

		$params = array(
			'action' => esc_url( site_url( $_SERVER['REQUEST_URI'] ) ),
			'method' => 'post'
		);

		if ( empty( $_SERVER['HTTPS'] ) )
			$params['action'] = str_replace( 'http://', '//', $params['action'] );

		$params['action'] = esc_attr( $params['action'] );

		Theme::set( "form-referer", $params['action'] );

		if ( Theme::isset( "form-custom" ) ) {
			if ( $_attrs = Layer::get_field( 'form_attrs' ) ) {
				$attrs = array();

				foreach ( $_attrs as $att )
					$attrs[$att['name']] = $att['value'];

				$params = wp_parse_args( $attrs, $params );
			}
		}

		if ( Theme::isset( "form-validation" ) )
			$params[$browser_validation] = '';

		return $params;
	}


	/**
	 * Data extras attributes for form fieldset
	 *
	 * @see \theme\get_data_extras()
	 *
	 * @param array|null $params
	 * @param string $type
	 * @param array $defaults
	 * @return array $params
	 */
	public function data_extras_form_fieldset( $params, $type, $defaults ) {
		//TODO ?
		$params = (array) $defaults;

		$view = Layer::get_subfield( 'view', false );

		Theme::set( "form-fieldset-view", $view );

		if ( empty( $params['class'] ) )
			$params['class'] = array();
		else if ( is_string( $params['class'] ) )
			$params['class'] = explode( ' ', $params['class'] );

		if ($view == 'cols')
			$params['class'][] = 'row';

		if ( Theme::isset( "form-custom" ) ) {
			if ( $_attrs = Layer::get_subfield( 'fieldset_attrs' ) ) {
				$attrs = array();

				foreach ( $_attrs as $att )
					$attrs[$att['name']] = $att['value'];

				$params = wp_parse_args( $attrs, $params );
			}
		}

		if ( is_array( $params['class'] ) )
			$params['class'] = implode( ' ', array_map( 'esc_attr', $params['class'] ) );
		else
			$params['class'] = esc_attr( $params['class'] );

		return $params;
	}


	/**
	 * Data extras attributes for form field
	 *
	 * @see \theme\get_data_extras()
	 *
	 * @param array|null $params
	 * @param string $type
	 * @param array $defaults
	 * @return array $params
	 */
	public function data_extras_form_field( $params, $type, $defaults ) {
		//TODO ?
		$params = (array) $defaults;

		$form_id = Theme::get( "latest:form" );

		/**
		 * Filter for form placeholder
		 *
		 * @param bool void
		 */
		$field_placeholder = apply_filters(
			'theme_form_field_placeholder',
			Theme::isset( "form-placeholders" ) || Layer::get_subfield( 'placeholder' )
		);

		if ( empty( $params['class'] ) )
			$params['class'] = array();
		else if ( is_string( $params['class'] ) )
			$params['class'] = explode( ' ', $params['class'] );

		if ( $type == 'input' || $type == 'textarea' || $type == 'select' ) {
			if ( $field_placeholder ) {
				if ( $label = Layer::get_subfield( 'label' ) )
					$params['placeholder'] = esc_attr( $label );
			}
		}

		/**
		 * Filter to match custom controls of css framework
		 *
		 * @param bool void
		 */
		if ( apply_filters( 'theme_form_bs_custom', '__return_true' ) ) {
			if ( $type == 'checkbox' ) {
				$params['class'][0] = 'custom-control-input';
			} else if ( $type == 'select' ) {
				$params['class'][0] = 'custom-select';
			}
		}

		if ( ! Theme::isset( "form-validation" ) && Layer::get_subfield( 'validate' ) ) {
			if ( $type == 'input' && Theme::isset( "form-custom" ) ) {
				if ( $pattern = Layer::get_subfield( 'pattern' ) )
					$params['pattern'] = esc_attr( $pattern );
			}

			$params['validate'] = '';
		}

		if ( $default = Layer::get_subfield( 'default' ) )
			$params['value'] = esc_attr( $default );

		if ( Layer::get_subfield( 'required' ) )
			$params['required'] = '';

		if ( Theme::isset( "form:custom" ) ) {
			if ( $_attrs = Layer::get_subfield( 'attrs' ) ) {
				$attrs = array();

				foreach ( $_attrs as $att )
					$attrs[$att['name']] = $att['value'];

				$params = wp_parse_args( $attrs, $params );
			}
		}

		if ( is_array( $params['class'] ) )
			$params['class'] = implode( ' ', array_map( 'esc_attr', $params['class'] ) );
		else
			$params['class'] = esc_attr( $params['class'] );

		return $params;
	}


	/**
	 * Data extras attributes for form button
	 *
	 * @see \theme\get_data_extras()
	 *
	 * @param array|null $params
	 * @param string $type
	 * @param array $defaults
	 * @return array $params
	 */
	public function data_extras_form_field_button( $params, $type, $defaults ) {
		$params = (array) $defaults;

		/**
		 * Filter for form type
		 *
		 * @param string void
		 */
		$field_type = apply_filters( 'theme_form_field_type', Layer::get_subfield( 'type' ) );

		if ( empty( $params['class'] ) )
			$params['class'] = array();
		else if ( is_string( $params['class'] ) )
			$params['class'] = explode( ' ', $params['class'] );

		$params['class'][] = ($field_type == 'submit' ? 'btn-primary' : 'btn-secondary');

		if ( Layer::get_subfield( 'element' ) == 'input' ) {
			if ( $label = Layer::get_subfield( 'label' ) )
				$params['value'] = esc_attr( $label );
		}

		if ( Theme::isset( "form-custom" ) ) {
			if ( $_attrs = Layer::get_subfield( 'attrs' ) ) {
				$attrs = array();

				foreach ( $_attrs as $att )
					$attrs[$att['name']] = $att['value'];

				$params = wp_parse_args( $attrs, $params );
			}
		}

		if ( is_array( $params['class'] ) )
			$params['class'] = implode( ' ', array_map( 'esc_attr', $params['class'] ) );
		else
			$params['class'] = esc_attr( $params['class'] );

		return $params;
	}


	/**
	 * Data extras attributes for form field select option
	 *
	 * @see \theme\get_data_extras()
	 *
	 * @param array|null $params
	 * @param string $type
	 * @param array $defaults
	 * @param array $args
	 * @return array $params
	 */
	public function data_extras_form_field_select_option( $params, $type, $defaults, $args ) {
		//TODO ?
		$params = (array) $defaults;
		$option = $args[1];

		if ( ! empty( $option['name'] ) )
			$params['name'] = esc_attr( $option['name'] );

		if ( $option['default'] )
			$params['selected'] = '';

		return $params;
	}


	/**
	 * Data extra attributes for wizard form
	 *
	 * @see \theme\get_data_extras()
	 *
	 * @param null|object $post - \WP_Post
	 * @param array|null $params
	 * @return array $params
	 */
	public function data_extras_form_wizard( $params ) {
		$pages = Layer::get_field( 'pages', null, true );

		if ( ! $pages )
			return $params;

		global $post;

		if ( $page = get_query_var( 'page' ) )
			$page = intval( $page );
		else
			$page = 1;

		$total = count( $pages );
		$i = $page;

		if ( $page !== $total )
			$i++;

		if ( '' == get_option( 'permalink_structure' ) || in_array( $post->post_status, array('draft', 'pending') ) )
			$action = add_query_arg( 'page', $i, get_permalink() );
		else
			$action = trailingslashit( get_permalink() ) . user_trailingslashit( $i, 'single_paged' );

		if ( false !== strpos( $params['action'], '?' ) )
			$action .= '?' . parse_url( $params['action'], PHP_URL_QUERY );

		$params['action'] = esc_attr( $action );

		if ( empty( $_SERVER['HTTPS'] ) )
			$params['action'] = str_replace( 'http://', '//', $params['action'] );

		if ( Layer::get_field( 'enable_pagination', false, true ) )
			$params['data-pagination'] = '';

		if ( isset( $params['wizard-ref'] ) ) {
			Theme::set( "form-wizard", esc_attr( $params['wizard-ref'] ) );

			unset( $params['wizard-ref'] );
		}

		return $params;
	}


	/**
	 * Adds legacy support for colors with an inline style attribute
	 *
	 * @param array|null $params
	 * @return array $params
	 */
	public function legacy_colors_inline_style( $params ) {
		if ( empty( $params ) )
			return $params;

		$styles = array();

		if ( isset( $params['data-foreground-color'] ) )
			$styles[] = 'color: ' . $params['data-foreground-color'] . ';';

		if ( isset( $params['data-background-color'] ) )
			$styles[] = 'background-color: ' . $params['data-background-color'] . ';';

		if ( ! empty( $styles ) )
			$params['style'] = implode( ' ', $styles );

		return $params;
	}


	/**
	 * Filters the modal header
	 *
	 * @param string $text
	 * @return string $text
	 */
	public function modal_header( $text ) {
		echo sprintf( '<h5 class="modal-title">%s</h5>', $text );
	}


	/**
	 * Filters the modal body
	 *
	 * @param string $text
	 * @return string $text
	 */
	public function modal_body( $text ) {
		echo apply_filters( 'the_content', $text );
	}


	/**
	 * Filters the modal footer
	 *
	 * @see \theme\Functions->page_mode_filters()
	 *
	 * @param string $text
	 * @return string $text
	 */
	public function modal_footer( $text ) {
		remove_filter( 'the_content', array($this, 'page_mode_modal_content') );

		echo apply_filters( 'the_content', $text );
	}


	/**
	 * Adds the close button to the modal header
	 */
	public function modal_header_close_button() {
		the_modal_close_button();
	}


	/**
	 * Filters the template include and override it with modal template variant if exists
	 *
	 * @see /wp-includes/template-loader.php
	 *
	 * @param string $template
	 * @return string $template
	 */
	public function page_mode_modal_template_override( $template ) {
		if ( $located = locate_template( 'page-templates/modal-' . basename( $template ) ) )
			$template = $located;

		return $template;
	}


	/**
	 * Filters title for page mode: Modal
	 *
	 * @see the_title()
	 *
	 * @param null|object $post - \WP_Post
	 * @param string $title
	 * @return string $title
	 */
	public function page_mode_modal_title( $title ) {
		global $post;

		//TODO improve (eg. heading classname)
		if ( isset( $post->post_content ) && false !== strpos( $post->post_content, '</h' ) ) {
			preg_match( '#<h([1-6])>(.+?)</h\1>#i', $post->post_content, $matches );

			if ( isset( $matches[2] ) )
				$title = $matches[2];
		}

		return $title;
	}


	/**
	 * Filters content for page mode: Modal
	 *
	 * @see the_content()
	 *
	 * @param string $content
	 * @return string $content
	 */
	public function page_mode_modal_content( $content ) {
		if ( false !== strpos( $content, '</h' ) )
			$content = preg_replace( '#<h([1-6])>(.+?)</h\1>#i', '', $content, 1 );

		return $content;
	}


	/**
	 * Sets the current page layer runtime loop, 
	 * placed at the beginning of the page layer loop
	 *
	 * @see ./theme/template-parts/page.php
	 */
	public function page_layer_loop_start() {
		$wrap = true;
		$wrap_class = null;

		$i = 0;

		if ( Theme::isset( "latest:layer" ) ) {
			$i = Theme::get( "latest:layer", 0 );
			$i = (int) $i + 1;
		}

		$selector = "layer_{$i}";

		Theme::set( "current:layer", $i );
		Theme::set( "latest:layer", $i );
		Theme::set( "latest:{$i}:layer", $selector );

		if ( $view = Layer::get_field( 'view', null, null, false ) )
			Theme::set( "layer-view", $view );

		if ( Layer::get_field( 'enable_custom', null, true ) )
			Theme::set( "layer-custom", true );
	}


	/**
	 * Resets the current page layer runtime loop, 
	 * placed at the end of the page layer loop
	 *
	 * @see ./theme/template-parts/page.php
	 */
	public function page_layer_loop_end() {
		Theme::set( "current:layer", -1 );

		Theme::unset( "layer-view" );
		Theme::unset( "layer-custom" );
	}


	/**
	 * Sets the current page block runtime loop, 
	 * placed at the beginning of the page block loop
	 *
	 * @see ./theme/template-parts/page.php
	 */
	public function page_block_loop_start() {
		$layer_i = Theme::get( "latest:layer" );

		$i = 0;

		if ( Theme::isset( "latest:{$layer_i}:block" ) ) {
			$i = Theme::get( "latest:{$layer_i}:block", 0 );
			$i = (int) $i + 1;
		}

		$selector = "block_{$layer_i}_{$i}";

		Theme::set( "latest:{$layer_i}:block", $i );
		Theme::set( "latest:{$layer_i}:{$i}:block", $selector );

		Theme::set( "current:block", $i );
	}


	/**
	 * Resets the current page block runtime loop, 
	 * placed at the end of the page block loop
	 *
	 * @see ./theme/template-parts/page.php
	 */
	public function page_block_loop_end() {
		Theme::set( "current:block", -1 );
	}


	/**
	 * Output wrap div open, 
	 * placed before the begin of the page layer loop
	 *
	 * @see ./theme/template-parts/page.php
	 */
	public function page_layer_content_start() {
		$view = Theme::get( "layer-view" );
		$wrap = true;

		switch ( $view ) {
			case 'centered' :
				$wrap_class = 'row-centered';
			break;

			case 'cols' :
				$wrap_class = 'row';
			break;

			default:
				$wrap = false;
		}

		if ( $wrap )
			echo "<div class=\"{$wrap_class}\">\n";
	}


	/**
	 * Output wrap div closure, 
	 * placed before the end of the page layer loop
	 *
	 * @see ./theme/template-parts/page.php
	 */
	public function page_layer_content_end() {
		$view = Theme::get( "layer-view" );
		$wrap = false;

		switch ( $view ) {
			case 'centered' :
				$wrap = true;
			break;

			case 'cols' :
				$wrap = true;
			break;
		}

		if ( $wrap )
			echo "</div>\n";
	}


	/**
	 * Resets the current runtime form ID value, 
	 * placed before the form
	 *
	 * @see ./theme/template-parts/form.php
	 * @see ./theme/template-parts/form-shortcode-block.php
	 */
	public function before_form() {
		Theme::set( "current:form", -1 );
	}


	/**
	 * Resets the current runtime slideshow ID value, 
	 * placed before the slideshow
	 *
	 * @see ./theme/template-parts/slideshow.php
	 * @see ./theme/template-parts/slideshow-shortcode-block.php
	 */
	public function before_slideshow() {
		Theme::set( "current:slideshow", -1 );
	}


	/**
	 * Adds an hidden field with the current forn ID value and an hidden field with the request referer, 
	 * placed after the form
	 *
	 * @see ./theme/template-parts/form.php
	 * @see ./theme/template-parts/form-shortcode-block.php
	 *
	 * @param string $form_id
	 * @return string $output
	 */
	public function after_form( $form_id ) {
		$form_id = esc_attr( base64_encode( $form_id ) );
		$form_ref = null;

		if ( Theme::isset( "form-referer" ) )
			$form_ref = esc_attr( base64_encode( Theme::get( "form-referer" ) ) );

		$output = wp_nonce_field( 'send-form', '_nonce', false, false );
		$output .= "<input type=\"hidden\" name=\"form-id\" value=\"{$form_id}\" />\n";

		if ( $form_ref )
			$output .= "<input type=\"hidden\" name=\"form-ref\" value=\"{$form_ref}\" />\n";

		echo $output;
	}


	/**
	 * Adds an hidden field with the current page number of the wizard processing, 
	 * placed after the form
	 *
	 * @see ./theme/template-parts/form.php
	 * @see ./theme/template-parts/form-shortcode-block.php
	 *
	 * @param string $form_id
	 * @return string $output
	 */
	public function wizard_after_form( $form_id ) {
		if ( $page = get_query_var( 'page' ) )
			$page = intval( $page );
		else
			$page = 1;

		$output = "<input type=\"hidden\" name=\"wizard\" value=\"{$page}\" />\n";

		if ( Theme::isset( "form-wizard" ) ) {
			$referal = Theme::get( "form-wizard" );

			$output .= "<input type=\"hidden\" name=\"wizard-ref\" value=\"{$referal}\" />\n";
		}

		echo $output;
	}


	/**
	 * Filters default comment form arguments to match the css framework behaviour
	 *
	 * @see comment_form()
	 *
	 * @param array $defaults
	 * @return array $defaults
	 */
	public function comment_form_defaults( $defaults ) {
		$defaults['class_submit'] = 'btn btn-secondary submit';
		$defaults['title_reply'] = __( 'Write a comment', 'theme' );
		$defaults['title_reply_to'] = __( 'Reply to %s', 'theme' );

		if ( empty( $_SERVER['HTTPS'] ) )
			$defaults['action'] = str_replace( 'http://', '//', $defaults['action'] );

		return $defaults;
	}


	/**
	 * Filters the comment form fields to match the css framework behaviour
	 *
	 * @see comment_form()
	 *
	 * @param array $comment_fields
	 * @return array $comment_fields
	 */
	public function comment_form_fields( $comment_fields ) {
		foreach ( $comment_fields as $name => $field )
			$comment_fields[$name] = str_replace( ' name=', ' class="form-control" name=', $field );

		return (array) $comment_fields; 
	}


	/**
	 * Filters the comment reply link to match the css framework behaviour
	 *
	 * @param string $link
	 * @return string $link
	 */
	public function comment_reply_link( $link ) {
		$link = str_replace( 'comment-reply-link', 'comment-edit-link btn btn-primary btn-sm', $link );
		return $link;
	}


	/**
	 * Filters the comment edit link
	 *
	 * @param string $link
	 * @return string $link
	 */
	public function edit_comment_link( $link ) {
		$link = str_replace( 'comment-edit-link', 'comment-edit-link btn btn-secondary btn-sm', $link );
		return $link;
	}


	/**
	 * Filters the comment cancel link
	 *
	 * @param string $formatted_link
	 * @return string $formatted_link
	 */
	public function cancel_reply_link( $formatted_link ) {
		$formatted_link = str_replace(
			'id="cancel-comment-reply-link"',
			'id="cancel-comment-reply-link" class="cancel-comment-reply-link btn btn-secondary btn-sm"',
			$formatted_link );

		return $formatted_link;
	}


	/**
	 * Filters the attachment link classname attribute
	 *
	 * @see wp_get_attachment_link()
	 *
	 * @param string $html
	 * @return string void
	 */
	public function attachment_link_class( $html ) {
		return str_replace( array('\'', 'href='), array('"', 'class="gallery-image" href='), $html );
	}


	/**
	 * Removes thumbnail width and height attributes
	 *
	 * @see get_the_post_thumbnail()
	 *
	 * @param string $html
	 * @param int $post_id
	 * @param int $post_thumbnail_id
	 * @param string|array $size
	 * @return string $html
	 */
	public function post_thumbnail_html_sizes( $html, $post_id = 0, $post_thumbnail_id = 0, $size = '' ) {
		if ( $this->theme->Functions->has_shop( 'WooCommerce' ) ) {
			if ( $size == 'shop_single' || $size == 'shop_thumbnail' )
				return $html;
		}

		$html = preg_replace( '/(width|height)=\"\d*\"\s/', '', $html );

		return $html;
	}


	/**
	 * Removes width and height from the generated image tag
	 *
	 * @see get_image_tag()
	 *
	 * @param string $html
	 * @return string void
	 */
	public function get_image_tag( $html ) {
		return preg_replace(
			array(
				'/\s+class="(.*?)"/i',
				'/\s+width="\d+"/i',
				'/\s+height="\d+"/i'
			),
			array(
				' class="$1"',
				'',
				''
			),
			$html
		);
	}


	/**
	 * Wraps the generated image tag and add caption
	 *
	 * @see get_image_send_to_editor()
	 *
	 * @param string $html
	 * @param int $id
	 * @param string $caption 
	 * @return string $html
	 */
	public function image_send_to_editor( $html, $id, $caption ) {
		$html = '<figure>' . $html;

		if ( $caption )
			$html .= '<figcaption>' . $caption . '</figcaption>';

		$html .= '</figure>';

		return $html;
	}


	/**
	 * Filters default pagination function arguments to match the css framework behaviour
	 *
	 * @see wp_link_pages()
	 *
	 * @param array $args
	 * @return array $args
	 */
	public function navigation_link_pages_args( $args ) {
		/**
		 * Filters all theme links related arguments
		 *
		 * @param array $args
		 * @param array $args {
		 *  	@type string ‘prev_text_label‘
		 * 		@type string ‘prev_text_icon‘
		 * 		@type string ‘next_text_label‘
		 *		@type string ‘next_text_icon‘
		 * ... }
		 * @param string $context
		 */
		$args = wp_parse_args( apply_filters( 'theme_links_defaults', array(
			'before' => "<nav class=\"page-links\" role=\"navigation\">\n%s<ul class=\"pagination\">\n",
			'after' => "</ul>\n</nav>\n",
			'separator' => '',
			'prev_text_label' => __( 'Previous' ),
			'prev_text_icon' => '&#9668;',
			'next_text_label' => __( 'Next' ),
			'next_text_icon' => '&#9658;',
			'screen_reader_text' => __( 'Pages' ),
		), 'wp-link-pages' ), $args );

		$args['before'] = sprintf(
			$args['before'],
			"<span class=\"sr-only sr-only-focusable\">" . $args['screen_reader_text'] . "</span>\n"
		);

		/**
		 * To show or not icons in all theme links
		 *
		 * @return bool void
		 */
		if ( apply_filters( 'theme_links_show_icons', true ) ) {
			$prev_text_label = $args['prev_text_label'];
			$prev_text_icon = $args['prev_text_icon'];
			$next_text_label = $args['next_text_label'];
			$next_text_icon = $args['next_text_icon'];

			$args['previouspagelink'] = "\n\t\t<span aria-hidden=\"true\">{$prev_text_icon}</span>\n";
			$args['previouspagelink'] .= "\t\t<span>{$prev_text_label}</span>\n\t";
			$args['nextpagelink'] = "\n\t\t<span>{$next_text_label}</span>\n";
			$args['nextpagelink'] .= "\t\t<span aria-hidden=\"true\">{$next_text_icon}</span>\n\t";
		}

		return $args;
	}


	/**
	 * Filters default pagination link to match the css framework behaviour
	 *
	 * @see wp_link_pages()
	 *
	 * @param string $link
	 * @return string $link
	 */
	public function navigation_link_pages_link( $link ) {
		$class = 'page-item';

		if ( false !== strpos( $link, 'href' ) ) {
			$link = str_replace( 'href=', 'class="page-link" href=', $link );
		} else {
			$class .= ' active';
			$link = "<span class=\"page-link current\">{$link}</span>";
		}

		$link = "<li class=\"{$class}\">\n\t{$link}\n</li>\n";

		return $link;
	}


	/**
	 * The default pagination markup
	 *
	 * @see _navigation_markup()
	 *
	 * @param string $template
	 * @return string $template
	 */
	public function navigation_markup_template( $template ) {
		$template = "<nav class=\"navigation %1\$s\" role=\"navigation\">\n";
		$template .= "<span class=\"sr-only sr-only-focusable\">%2\$s</span>\n";
		$template .= "<ul class=\"pagination nav-links\">\n%3\$s</ul>\n</nav>\n";

		return $template;
	}


	/**
	 * Filters the previous comments page link tag attributes
	 *
	 * @see get_previous_comments_link()
	 *
	 * @param string $output
	 * @return string void
	 */
	public function navigation_link_prev_class( $output = '' ) {
		return "class=\"page-link next\"";
	}


	/**
	 * Filters the next comments page link tag attributes
	 *
	 * @see get_next_comments_link()
	 *
	 * @param string $output
	 * @return string void
	 */
	public function navigation_link_next_class( $output = '' ) {
		return "class=\"page-link prev\"";
	}


	/**
	 * Removes itemscope microdata from navigation element,
	 * for “wp-bootstrap-navwalker“
	 *
	 * @see wp_nav_menu()
	 * @see \WP_Bootstrap_Navwalker->start_el()
	 *
	 * @param string items
	 * @return string void
	 */
	public function remove_itemscope_nav_el( $items ) {
		return str_replace(
			' itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement"',
			'',
			$items
		);
	}


	/**
	 * Adds the css framework table formatting to the calendar widget
	 *
	 * @see get_calendar()
	 *
	 * @param string $calendar_output
	 * @param string void
	 */
	public function get_calendar( $calendar_output ) {
		return str_replace(
			'id="wp-calendar"',
			'id="wp-calendar" class="table table-bordered text-center"',
			$calendar_output
		);
	}


	/**
	 * Removes inline styles from the Tag Cloud Widget
	 *
	 * @see \WP_Widget_Tag_Cloud->widget()
	 * @see wp_tag_cloud()
	 * @see wp_generate_tag_cloud()
	 *
	 * @param string $tag_string
	 * @return string void
	 */
	public function remove_tag_cloud_inline_styles( $tag_string ){
		return preg_replace( "/ style='font-size:.+pt;'/", "", $tag_string );
	}


	/**
	 * Removes post ID from body classname when unexpone option is enabled
	 *
	 * //TODO parent page id
	 *
	 * @see get_body_class()
	 *
	 * @param null|object $post - \WP_Post
	 * @param array $classes
	 * @return array $classes
	 */
	public function unexpone_id_body_class( $classes ) {
		if ( ! is_singular() )
			return $classes;

		global $post;

		if ( $post && $post->ID ) {
			$found_post_class_key = 0;
			$is_page_template = is_page_template();

			foreach ( $classes as $key => $class ) {
				if ( $found_post_class_key === 3 )
					break;

				if ( false !== strpos( $class, '-' . $post->ID ) ) {
					if ( $post->post_name )
						$classes[$key] = $post->post_type . '-' . str_replace( '-', '', $post->post_name );
					else
						unset( $classes[$key] );

					$found_post_class_key += ( $is_page_template ? 1 : 3 );

					continue;
				}

				if ( $is_page_template ) {
					if ( false !== strpos( $class, '-page-templates' ) ) {
						unset( $classes[$key] );

						$found_post_class_key++;
					}
				}
			}
		}

		return $classes;
	}

	/**
	 * Removes the post ID from element classname when unexpone option is enabled
	 *
	 * @see get_post_class()
	 *
	 * @param null|object $post - \WP_Post
	 * @param array $classes
	 * @param mixed $class
	 * @return array $classes
	 */
	public function unexpone_id_post_class( $classes, $class ) {
		global $post;

		$found_post_class_key = 0;

		if ( $class ) {
			if ( isset( $class[0] ) )
				$found_post_class_key = count( $class );
			else
				$found_post_class_key = 1;
		}

		if ( $post && $post->post_name )
			$classes[$found_post_class_key] = $post->post_type . '-' . str_replace( '-', '', $post->post_name );
		else
			unset( $classes[$found_post_class_key] );

		return $classes;
	}

	/**
	 * Removes the post ID from menu item element id when unexpone option is enabled
	 *
	 * @see \Walker_Nav_Menu
	 *
	 * @global string $item_id
	 * @param object $item - \WP_Post
	 * @return string $item_id
	 */
	public function unexpone_id_nav_menu_item_id( $item_id, $item ) {
		if ( $item->type == 'custom' ) {
			$item_id = 'menu-item-' . sanitize_key(
				( $item->attr_title ? $item->attr_title : strip_tags( $item->title ) )
			);
		} else {
			$item_id = 'menu-item-' . sanitize_key( $item->title );
		}

		return $item_id;
	}

	/**
	 * Removes the post ID from menu item element class when enabled the unexpone option
	 *
	 * //TODO FIX (es. 'menu-item-object-custom' ERR)
	 *
	 * @see \Walker_Nav_Menu
	 *
	 * @global array $classes
	 * @param object $item - \WP_Post
	 * @return string $item_id
	 */
	public function unexpone_id_nav_menu_item_class( $classes, $item ) {
		$item_menu_id_key = 'menu-item-' . $item->ID;
		$found_menu_id_key = 0;

		unset( $classes[2] );

		if ( isset( $classes[4] ) && $classes[4] === $item_menu_id_key )
			$found_menu_id_key = 4;
		else if ( isset( $classes[5] ) && $classes[5] === $item_menu_id_key )
			$found_menu_id_key = 5;
		else if ( isset( $classes[8] ) && $classes[8] === $item_menu_id_key )
			$found_menu_id_key = 8;
		else if ( isset( $classes[9] ) && $classes[9] === $item_menu_id_key )
			$found_menu_id_key = 9;

		if ( $found_menu_id_key ) {
			$current_menu_item_key = ( $found_menu_id_key == 4 || $found_menu_id_key == 9 ) ? 5 : 4;

			if ( isset( $classes[$current_menu_item_key] ) && $classes[$current_menu_item_key] === 'current-menu-item' ) {
				if ( isset( $classes[6] ) )
					unset( $classes[6] );

				if ( isset( $classes[8] ) )
					unset( $classes[8] );

				if ( $found_menu_id_key > 5 ) {
					$current_menu_item_key++;
					unset( $classes[$current_menu_item_key++] );
					unset( $classes[$current_menu_item_key++] );
					unset( $classes[$current_menu_item_key++] );
				}
			}

			unset( $classes[$found_menu_id_key] );
		}

		return $classes;
	}

	/**
	 * The default data ID with slug
	 *
	 * @see \theme\get_data_ID()
	 *
	 * @param null|object $post - \WP_Post
	 * @param string $context
	 * @return string void
	 */
	public function unexpone_id_data_id_default( $context ) {
		global $post;

		if ( $post->post_name )
			$slug = str_replace( '-', '', $post->post_name );
		else
			$slug = $context;

		return $slug;
	}


}

new Template;