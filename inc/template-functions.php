<?php
/**
 * theme template functions
 *
 * @package theme
 * @version 1.0
 */

 namespace theme;

 use \theme\Functions;


/**
 * Gets the page mode
 *
 * @see \theme\Functions::page_mode()
 *
 * @return string|null void
 */
function get_page_mode() {
	return Functions::get_page_mode();
}


/**
 * Gets the page template name
 *
 * @param int|object $post - WP_Post
 * @return string|null void
 */
function get_page_template_name( $post = null ) {
	$template_slug = null;

	if ( $template_slug = get_page_template_slug() )
		$template_slug = str_replace( array('.', '/'), '-', basename( $template_slug, '.php' ) );

	return $template_slug;
}


/**
 * Loads specific header template [ajax, page layout, default], 
 * overrides the built-in \get_header() function
 *
 * @return string|void void
 */
function get_header() {
	if ( $page_mode = \theme\get_page_mode() )
		return \get_header( $page_mode );

	if ( Functions::is_ajax_mode() )
		return \get_header( 'ajax' );

	\get_header();
}


/**
 * Loads specific footer template [ajax, page layout, default], 
 * overrides the built-in \get_footer() function
 *
 * @return string|void void
 */
function get_footer() {
	if ( $page_mode = get_page_mode() )
		return \get_footer( $page_mode );

	if ( Functions::is_ajax_mode() )
		return \get_footer( 'ajax' );

	\get_footer();
}


/**
 * Wrapper to return data ID attribute
 *
 * @param string $name
 * @param string $context
 * @return int|bool $id
 */
function get_data_ID( $name = 'default', $context = '' ) {
	return apply_filters( "theme_data_id_{$name}", $context );
}


/**
 * Displays the data ID attribute
 *
 * @param string $name
 * @return string $context
 */
function the_data_ID( $name = 'default', $context = '' ) {
	echo get_data_ID( $name, $context );
}


/**
 * Returns the data classname attribute
 *
 * @param string $name
 * @param string|array $classname
 * @return array $classes
 */
function get_data_class( $name = 'default', $class = '' ) {
	if ( $class ) {
		if ( ! is_array( $class ) )
			$class = explode( ' ', $class );
	} else {
		$class = array();
	}

	$classes = array_map( 'esc_attr', $class );

	/**
	 * Filters classname(s) with the ‘name‘ suffix
	 *
	 * @param array $classes
	 */
	$classes = apply_filters( "theme_data_class_{$name}", $classes );

	return (array) $classes;
}


/**
 * Displays the data classname attribute
 *
 * @param string $name
 * @param string|array $class
 */
function the_data_class( $name = 'default', $class = '' ) {
	$class = get_data_class( $name, $class );

	if (! empty( $class ) )
		echo 'class="' . implode( ' ', $class ) . '"';
}


/**
 * Returns data extra attributes
 *
 * @param string $name
 * @param string $type
 * @param array $defaults
 * @param mixed $args
 * @return array $params
 */
function get_data_extras( $name = 'default', $type = '', $defaults = array(), $args = '' ) {
	$type = (string) $type;

	if ( '' !== $type )
		$name .= "_{$type}";

	/**
	 * Filters extra attributes with the ‘name‘ suffix
	 *
	 * @param array|null $params
	 * @param string $type
	 * @param array $defaults
	 * @param mixed $args
	 */
	$params = null;
	$params = apply_filters( "theme_data_extras_{$name}", $params, $type, $defaults, $args );
	$params = wp_parse_args( $params, $defaults );

	return (array) $params;
}


/**
 * Displays data extra attributes
 *
 * @param string $name
 * @param string $type
 * @param array $defaults
 * @param mixed $args
 */
function the_data_extras( $name = 'default', $type = '', $defaults = array(), $args = '' ) {
	$params = get_data_extras( $name, $type, $defaults, $args );

	if ( ! empty( $params ) ) {
		$attributes = array();

		foreach ( $params as $key => $value ) {
			if ( ! $value )
				$attributes[] = "{$key}";
			else
				$attributes[] = "{$key}=\"{$value}\"";
		}

		echo ' ' . implode( ' ', $attributes );
	}
}


/**
 * Creates paginated links matching the css framework behaviour, 
 * overrides the built-in /paginate_links() function
 *
 * @see /wp-includes/general-template.php
 *
 * @param string|array $src_args
 * @return string|array $r
 */
function paginate_links( $src_args = '' ) {

	/**
	 * Filters all theme links related arguments
	 *
	 * @param array $args
	 *  	@type string ‘prev_text_label‘
	 * 		@type string ‘prev_text_icon‘
	 * 		@type string ‘next_text_label‘
	 *		@type string ‘next_text_icon‘
	 * ... }
	 * @param string $context
	 */
	$src_args = wp_parse_args( $src_args, apply_filters( 'theme_links_defaults', array(
		'type' => 'list',
		'prev_text_label' => __( 'Previous' ),
		'prev_text_icon' => '&#9668;',
		'next_text_label' =>  __( 'Next' ),
		'next_text_icon' => '&#9658;'
	), 'paginate-links' ) );

	$defaults = array( 'type' => 'array' );

	$prev_text_label = $src_args['prev_text_label'];
	$next_text_label = $src_args['next_text_label'];

	/**
	 * To show or not icons in all theme links
	 *
	 * @return bool void
	 */
	if ( apply_filters( 'theme_links_show_icons', true ) ) {
		$prev_text_icon = $src_args['prev_text_icon'];
		$next_text_icon = $src_args['next_text_icon'];

		$defaults['prev_text'] = "\n\t\t<span aria-hidden=\"true\">{$prev_text_icon}</span>";
		$defaults['prev_text'] .= "\t\t<span class=\"sr-only\">{$prev_text_label}</span>\n\t";
		$defaults['next_text'] = "\n\t\t<span class=\"sr-only\">{$next_text_label}</span>\n";
		$defaults['next_text'] .= "\t\t<span aria-hidden=\"true\">{$next_text_icon}</span>\n\t";
	}

	$args = wp_parse_args( $defaults, $src_args );

	$page_links = \paginate_links( $args );
	$r = '';

	switch ( $src_args['type'] ) {
		case 'array' :
			return $page_links;

		case 'list' :
			foreach ( $page_links as $page_link ) {
				$item_class = 'page-item';
				$item_link = str_replace(
					array(
						'\'',
						"class=\"prev",
						"page-numbers",
						"class=\"next"
					),
					array(
						'"',
						"aria-label=\"{$prev_text_label}\" class=\"prev",
						"page-link page-numbers",
						"aria-label=\"{$next_text_label}\" class=\"next"
					),
					$page_link
				);

				if ( strstr( $item_link, 'current' ) )
					$item_class .= ' active';

				$r .= "<li class=\"{$item_class}\">\n\t{$item_link}\n</li>\n";
			}
		break;

		default :
			$r = join( "\n", $page_links );
	}

	return $r;
}


/**
 * Creates paginated links for the comments matching the css framework behaviour, 
 * overrides the built-in /paginate_comments_link() function
 *
 * @see /wp-includes/comments-template.php
 *
 * @global object $wp_rewrite - WP_Rewrite
 * @param string|array $args
 * @return string|void $page_links
 */
function paginate_comments_links( $args = array() ) {
	global $wp_rewrite;

	if ( ! is_singular() )
		return;

	$page = get_query_var('cpage');

	if ( !$page )
		$page = 1;

	$max_page = get_comment_pages_count();

	$defaults = array(
		'base' => add_query_arg( 'cpage', '%#%' ),
		'format' => '',
		'total' => $max_page,
		'current' => $page,
		'echo' => true,
		'add_fragment' => '#comments'
	);

	if ( $wp_rewrite->using_permalinks() ) :
		$defaults['base'] = user_trailingslashit(
			trailingslashit( get_permalink() ) . $wp_rewrite->comments_pagination_base . '-%#%', 'commentpaged'
		);
	endif;

	$args = wp_parse_args( $args, $defaults );
	$page_links = paginate_links( $args );

	if ( $args['echo'] ) :
		echo $page_links;
	else :
		return $page_links;
	endif;
}


/**
 * Retrieves embedded object(s) in the post content 
 *
 * @return string $objects
 */
function get_post_objects() {
	/**
	 * Filters the post content,
	 * from /the_content()
	 *
	 * @param string $content
	 */
	$content = apply_filters( 'the_content', get_the_content() );
	$data = false;

	if ( false === strpos( $content, 'wp-playlist-script' ) )
		$objects = get_media_embedded_in_content( $content );

	/**
	 * Filter to get specific index of found objects
	 *
	 * @param int void
	 * @param string|array $objects
	 */
	$index_object_to_get = apply_filters( 'theme_get_post_objects_index', 0, $objects );

	if ( is_array( $objects ) )
		$objects = $objects[$index_object_to_get];

	return $objects;
}


/**
 * Returns a specific post format output
 *
 * @return string $post_format
 * @return string void
 */
function get_post_format_data( $post_format ) {
	$data = '';

	if ( ! $post_format )
		$post_format = get_post_format();

	switch ( $post_format ) {
		case 'audio' :
		case 'video' :
			$data = get_post_objects();
		break;

		case 'gallery' :
			$data = get_post_gallery();
			$data = "<div class=\"card-body\">\n{$data}</div>\n";
		break;

		case 'image' :
			$data = get_the_post_thumbnail(
				null,
				'medium-wide',
				array( 'class' => 'card-img-top' )
			);
		break;

		case 'aside' :
		case 'chat' :
		case 'link' :
		case 'quote' :
		case 'status' :
			$data = get_the_content();

		case 'quote' :
			/**
			 * Filters the post format data for blockquote
			 *
			 * @param string void
			 */
			$data = apply_filters( 'theme_format_blockquote', get_the_content() );

			$data = "<div class=\"card-body card-text\">\n{$data}</div>\n";
		break;
	}

	/**
	 * Filters the post format data output
	 *
	 * @param string $data
	 * @param string $post_format
	 */
	return apply_filters( 'theme_post_format_data', $data, $post_format );
}
