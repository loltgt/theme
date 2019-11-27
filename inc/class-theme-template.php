<?php
/**
 * theme templating functions
 *
 * @package theme
 * @version 2.0
 */

namespace theme;

use \theme\Theme;


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

		! is_admin() && $this->template_frontend();

		Theme::register( "Template", $this );

	}



	/**
	 * Front-end template related
	 */
	public function template_frontend() {

		add_filter( 'theme_post_footer', array($this, 'theme_post_footer') );

		add_filter( 'the_content', array($this, 'format_blockquote') );
		add_filter( 'excerpt_more', array($this, 'excerpt_more') );
		add_filter( 'wp_get_attachment_link', array($this, 'attachment_link_class') );
		add_filter( 'post_thumbnail_html', array($this, 'post_thumbnail_html_sizes'), 10, 4 );
		add_filter( 'get_calendar', array($this, 'get_calendar') );
		add_filter( 'show_recent_comments_widget_style', '__return_false' );
		add_filter( 'wp_generate_tag_cloud', array($this, 'remove_tag_cloud_inline_styles') );

		add_filter( 'nav_menu_css_class', array($this, 'nav_item_css_class') );
		add_filter( 'nav_menu_link_attributes', array($this, 'nav_link_attributes') );

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

	}



	/**
	 * Allow post header in single posts
	 *
	 * @see the_content()
	 *
	 * @param string $content
	 * @return string $content
	 */
	public function theme_post_footer() {
		return is_single();
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
	 * Adds the css nav item class in nav item
	 *
	 * @param array $classes
	 * @return array $classes
	 */
	public function nav_item_css_class( $classes ) {
		$classes[] = 'nav-item';

		if ( strpos( implode( ' ', $classes ), 'current-' ) )
			$classes[] = 'active';

		return $classes;
	}


	/**
	 * Adds the css nav link class in nav link
	 *
	 */
	public function nav_link_attributes( $atts ) {
		$atts['class'] = 'nav-link';

		return $atts;
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
	 * @return string $html
	 */
	public function post_thumbnail_html_sizes( $html ) {
		$html = preg_replace( '/(width|height)=\"\d*\"\s/', '', $html );

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
		$template = "\n<nav class=\"navigation %1\$s\" aria-label=\"%4\$s\">\n";
		$template .= "<span class=\"sr-only sr-only-focusable\" aria-hidden=\"true\">%2\$s</span>\n";
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


}

new Template;