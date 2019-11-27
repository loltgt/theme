<?php
/**
 * theme template tags
 *
 * @package theme
 * @version 2.0
 */
 
 namespace theme;

 use \theme\Theme;


/**
 * Builds the navigation to next/previous post matching the css framework behaviour, 
 * overrides the built-in \get_the_post_navigation() function
 *
 * @see /wp-includes/link-template.php
 *
 * @param array $args
 * @return string $navigation
 */
if ( ! function_exists( '\theme\get_the_post_navigation' ) ) {
	function get_the_post_navigation( $args = array() ) {
		$navigation = '';

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
		$args = wp_parse_args( $args, apply_filters( 'theme_links_defaults', array(
			'prev_text_label' => __( 'Previous' ),
			'prev_text_icon' => '&#9668;',
			'next_text_label' => __( 'Next' ),
			'next_text_icon' => '&#9658;',
			'prev_text' => sprintf( _x( '%1$s: "%2$s"', 'post-navigation', 'theme' ), '%s', '%%title' ),
			'next_text' => sprintf( _x( '%1$s: "%2$s"', 'post-navigation', 'theme' ), '%s', '%%title' ),
			'in_same_term' => false,
			'excluded_terms' => '',
			'taxonomy' => 'category',
			'screen_reader_text' => __( 'Post navigation' ),
		), 'post-navigation' ) );

		$args['prev_text'] = ' title="' . esc_attr( sprintf( $args['prev_text'], $args['prev_text_label'] ) ) . '">';
		$args['next_text'] = ' title="' . esc_attr( sprintf( $args['next_text'], $args['next_text_label'] ) ) . '">';
		$args['prev_text_alt'] = $args['prev_text_label'];
		$args['next_text_alt'] = $args['next_text_label'];

		/**
		 * To show or not icons in all theme links
		 *
		 * @return bool void
		 */
		if ( apply_filters( 'theme_links_show_icons', true ) ) :
			$prev_text_label = $args['prev_text_label'];
			$prev_text_icon = $args['prev_text_icon'];
			$next_text_label = $args['next_text_label'];
			$next_text_icon = $args['next_text_icon'];

			$args['prev_text_alt'] = "\n\t\t<span aria-hidden=\"true\">{$next_text_icon}</span>\n";
			$args['prev_text_alt'] .= "\t\t<span class=\"sr-only\">{$prev_text_label}</span>\n\t";
			$args['next_text_alt'] = "\n\t\t<span class=\"sr-only\">{$next_text_label}</span>\n";
			$args['next_text_alt'] .= "\t\t<span aria-hidden=\"true\">{$prev_text_icon}</span>\n\t";
		endif;

		$prev_link = get_previous_post_link(
			'%link',
			$args['prev_text'] . $args['prev_text_alt'],
			$args['in_same_term'],
			$args['excluded_terms'],
			$args['taxonomy']
		);

		$next_link = get_next_post_link(
			'%link',
			$args['next_text'] . $args['next_text_alt'],
			$args['in_same_term'],
			$args['excluded_terms'],
			$args['taxonomy']
		);

		if ( $prev_link )
			$prev_link = str_replace( '> ', ' ', $prev_link );
		else
			$prev_link = '<span class="disabled">' . $args['prev_text_alt'] . '</span>';

		if ( $next_link )
			$next_link = str_replace( '> ', ' ', $next_link );
		else
			$next_link = '<span class="disabled">' . $args['next_text_alt'] . '</span>';

		$navigation .= "<li class=\"page-item page-item-next\">\n\t{$next_link}\n</li>\n";
		$navigation .= "<li class=\"page-item page-item-prev\">\n\t{$prev_link}\n</li>\n";

		$navigation = _navigation_markup( $navigation, 'post-navigation', $args['screen_reader_text'] );

		return $navigation;
	}
}


/**
 * Displays the navigation to next/previous post matching the css framework behaviour, 
 * overrides the built-in \the_post_navigation() function
 *
 * @see /wp-includes/link-template.php
 *
 * @param array $args
 */
if ( ! function_exists( '\theme\the_post_navigation' ) ) {
	function the_post_navigation( $args = array() ) {
		echo \theme\get_the_post_navigation( $args );
	}
}


/**
 * Builds the navigation to next/previous set of posts matching the css framework behaviour, 
 * overrides the built-in \get_the_posts_navigation() function
 *
 * @see /wp-includes/link-template.php
 *
 * @global object $wp_query - \WP_Query
 * @param array $args
 * @return string $navigation
 */
if ( ! function_exists( '\theme\get_the_posts_navigation' ) ) {
	function get_the_posts_navigation( $args = array() ) {
		global $wp_query;

		$navigation = '';

		if ( $wp_query->max_num_pages < 2 )
			return $navigation;

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
		$args = wp_parse_args( $args, apply_filters( 'theme_links_defaults', array(
			'prev_text_label' => _x( 'Previous', 'previous set of posts' ),
			'prev_text_icon' => '&#9668;',
			'next_text_label' => _x( 'Next', 'next set of posts' ),
			'next_text_icon' => '&#9658;',
			'screen_reader_text' => __( 'Posts navigation' ),
		), 'posts-navigation' ) );

		/**
		 * To show or not icons in all theme links
		 *
		 * @return bool void
		 */
		if ( apply_filters( 'theme_links_show_icons', true ) ) :
			$prev_text_label = $args['prev_text_label'];
			$prev_text_icon = $args['prev_text_icon'];
			$next_text_label = $args['next_text_label'];
			$next_text_icon = $args['next_text_icon'];

			$args['prev_text'] = "\n\t\t<span aria-hidden=\"true\">{$prev_text_icon}</span>\n";
			$args['prev_text'] .= "\t\t<span>{$prev_text_label}</span>\n\t";
			$args['next_text'] = "\n\t\t<span>{$next_text_label}</span>\n";
			$args['next_text'] .= "\t\t<span aria-hidden=\"true\">{$next_text_icon}</span>\n\t";
		endif;

		$next_link = get_previous_posts_link( $args['next_text'] );
		$prev_link = get_next_posts_link( $args['prev_text'] );

		if ( $prev_link )
			$navigation .= "<li class=\"page-item\">\n\t{$prev_link}\n</li>\n";

		if ( $next_link )
			$navigation .= "<li class=\"page-item\">\n\t{$next_link}\n</li>\n";

		$navigation = _navigation_markup( $navigation, 'posts-navigation', $args['screen_reader_text'] );

		return $navigation;
	}
}


/**
 * Displays the navigation to next/previous set of posts matching the css framework behaviour, 
 * overrides the built-in \get_posts_navigation() function
 *
 * @see /wp-includes/link-template.php
 *
 * @param array $args
 */
if ( ! function_exists( '\theme\the_posts_navigation' ) ) {
	function the_posts_navigation( $args = array() ) {
		echo \theme\get_the_posts_navigation( $args );
	}
}


/**
 * Builds the paginated navigation to adjacent posts matching the css framework behaviour, 
 * overrides the built-in \get_the_posts_pagination() function
 *
 * @see /wp-includes/link-template.php
 *
 * @global object $wp_query - \WP_Query
 * @param array $args
 * @return string $navigation
 */
if ( ! function_exists( '\theme\get_the_posts_pagination' ) ) {
	function get_the_posts_pagination( $args = array() ) {
		global $wp_query;

		$navigation = '';

		if ( $wp_query->max_num_pages < 2 )
			return $navigation;

		/**
		 * Filters all theme links related arguments
		 *
		 * @param array $args
		 * @param string $context
		 */
		$args = wp_parse_args( $args, apply_filters( 'theme_links_defaults', array(
			'mid_size' => 1,
			'prev_text' => _x( 'Previous', 'previous set of posts' ),
			'next_text' => _x( 'Next', 'next set of posts' ),
			'screen_reader_text' => __( 'Posts navigation' )
		), 'pagination' ) );

		if ( isset( $args['type'] ) && 'array' == $args['type'] )
			$args['type'] = 'plain';

		$links = \theme\paginate_links( $args );

		if ( $links )
			$navigation = _navigation_markup( $links, 'posts-pagination', $args['screen_reader_text'] );

		return $navigation;
	}
}


/**
 * Displays the paginated navigation to adjacent posts matching the css framework behaviour, 
 * overrides the built-in \the_posts_pagination() function
 *
 * @see /wp-includes/link-template.php
 *
 * @param array $args
 */
if ( ! function_exists( '\theme\the_posts_pagination' ) ) {
	function the_posts_pagination( $args = array() ) {
		echo \theme\get_the_posts_pagination( $args );
	}
}


/**
 * Builds the navigation to next/previous set of comments matching the css framework behaviour, 
 * overrides the built-in \get_the_comments_navigation() function
 *
 * @see /wp-includes/link-template.php
 *
 * @param array $args
 * @return string $navigation
 */
if ( ! function_exists( '\theme\get_the_comments_navigation' ) ) {
	function get_the_comments_navigation( $args = array() ) {
		$navigation = '';

		if ( get_comment_pages_count() < 2 )
			return $navigation;

		/**
		 * Filters all theme links related arguments
		 *
		 * @param array $args
		 * @param string $context
		 */
		$args = wp_parse_args( $args, apply_filters( 'theme_links_defaults', array(
			'prev_text' => __( 'Older comments' ),
			'next_text' => __( 'Newer comments' ),
			'screen_reader_text' => __( 'Comments navigation' ),
		), 'comment-navigation' ) );

		/**
		 * To show or not icons in all theme links
		 *
		 * @return bool void
		 */
		if ( apply_filters( 'theme_links_show_icons', true ) ) :
			$prev_text_label = $args['prev_text_label'];
			$prev_text_icon = $args['prev_text_icon'];
			$next_text_label = $args['next_text_label'];
			$next_text_icon = $args['next_text_icon'];

			$args['prev_text'] = "\n\t\t<span aria-hidden=\"true\">{$prev_text_icon}</span>\n";
			$args['prev_text'] .= "\t\t<span class=\"sr-only\">{$prev_text_label}</span>\n\t";
			$args['next_text'] = "\n\t\t<span class=\"sr-only\">{$next_text_label}</span>\n";
			$args['next_text'] .= "\t\t<span aria-hidden=\"true\">{$next_text_icon}</span>\n\t";
		endif;

		$prev_link = get_previous_comments_link( $args['prev_text'] );
		$next_link = get_next_comments_link( $args['next_text'] );

		if ( $prev_link )
			$navigation .= "<li class=\"page-item\">\n\t{$prev_link}\n</li>\n";

		if ( $next_link )
			$navigation .= "<li class=\"page-item\">\n\t{$next_link}\n</li>\n";

		$navigation = _navigation_markup( $navigation, 'comment-navigation', $args['screen_reader_text'] );

		return $navigation;
	}
}


/**
 * Displays the navigation to next/previous set of comments matching the css framework behaviour, 
 * overrides the built-in \the_comments_navigation() function
 *
 * @see /wp-includes/link-template.php
 *
 * @param array $args
 */
if ( ! function_exists( '\theme\the_comments_navigation' ) ) {
	function the_comments_navigation( $args = array() ) {
		echo \theme\get_the_comments_navigation( $args );
	}
}


/**
 * Builds the paginated navigation to adjacent comments matching the css framework behaviour, 
 * overrides the built-in \get_the_comments_pagination() function
 *
 * @see /wp-includes/link-template.php
 *
 * @param array $args
 * @return string $navigation
 */
if ( ! function_exists( '\theme\get_the_comments_pagination' ) ) {
	function get_the_comments_pagination( $args = array() ) {
		$navigation = '';

		if ( get_comment_pages_count() < 2 )
			return $navigation;

		/**
		 * Filters all theme links related arguments
		 *
		 * @param array $args
		 * @param string $context
		 */
		$args = wp_parse_args( $args, apply_filters( 'theme_links_defaults', array(
			'screen_reader_text' => __( 'Comments navigation' ),
			'echo' => false
		), 'comments-pagination' ) );

		if ( isset( $args['type'] ) && 'array' == $args['type'] )
			$args['type'] = 'plain';

		$links = \theme\paginate_comments_links( $args );

		if ( $links )
			$navigation = _navigation_markup( $links, 'comments-pagination', $args['screen_reader_text'] );

		return $navigation;
	}
}


/**
 * Displays the paginated navigation to adjacent comments matching the css framework behaviour, 
 * overrides the built-in \the_comments_pagination() function
 *
 * @see /wp-includes/link-template.php
 *
 * @param array $args
 */
if ( ! function_exists( '\theme\the_comments_pagination' ) ) {
	function the_comments_pagination( $args = array() ) {
		echo \theme\get_the_comments_pagination( $args );
	}
}


/**
 * Builds the post edit link
 *
 * @see edit_post_link()
 *
 * @param string $class
 * @return string $edit_link
 */
if ( ! function_exists( '\theme\the_edit_link' ) ) {
	function get_the_edit_link( $class = 'post-edit-link' ) {
		$url = get_edit_post_link();

		if ( ! $url )
			return;

		/**
		 * Filters all theme links related arguments
		 *
		 * @param array $args
		 * @param string $context
		 */
		$args = apply_filters( 'theme_links_defaults', array(
			'edit_link_icon' => '&#9998;',
			'edit_link_label' => __( 'Edit This' ),
		), 'edit-link' );

		$url = esc_attr( $url );
		$class = esc_attr( $class );
		$text = $args['edit_link_label'];
		$label = esc_attr( $args['edit_link_label'] );

		/**
		 * To show or not icons in all theme links
		 *
		 * @return bool void
		 */
		if ( apply_filters( 'theme_links_show_icons', true ) ) :
			$icon = $args['edit_link_icon'];

			$text = "\n\t\t<span aria-hidden=\"true\">{$icon}</span>\n";
			$text .= "\t\t<span class=\"sr-only\">{$label}</span>\n\t";
		endif;

		$edit_link = "<a class=\"{$class} edit-link btn btn-edit-link\" href=\"{$url}\" title=\"{$label}\">{$text}</a>";

		/**
		 * Filters edit link
		 *
		 * @param string $link
		 * @param int void - \WP_Post
		 * @param string $label
		 */
		$edit_link = apply_filters( 'edit_post_link', $edit_link, get_the_ID(), $label );

		return $edit_link;
	}
}


/**
 * Displays the post edit link
 *
 * @see edit_post_link()
 *
 * @param string $class
 */
if ( ! function_exists( '\theme\the_edit_link' ) ) {
	function the_edit_link( $class = 'post-edit-link' ) {
		echo \theme\get_the_edit_link( $class );
	}
}


/**
 * Gets the published date for the post
 *
 * @param string $class
 * @return string $time_link
 */
if ( ! function_exists( '\theme\get_post_time_link' ) ) {
	function get_post_time_link( $class = 'published' ) {
		$class = esc_attr( $class );

		$time = "<time class=\"{$class} updated\" datetime=\"%1\$s\">%2\$s</time>\n";

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) :
			$time = "<time class=\"{$class}\" datetime=\"%1\$s\">%2\$s</time>\n";
			$time .= "<time class=\"updated\" datetime=\"%3\$s\" hidden>%4\$s</time>";
		endif;

		$time = sprintf( $time,
			get_the_date( DATE_W3C ),
			get_the_date(),
			get_the_modified_date( DATE_W3C ),
			get_the_modified_date()
		);

		$time_link = "<a href=\"" . esc_attr( get_permalink() ) . "\" rel=\"bookmark\">{$time}</a>";

		return $time_link;
	}
}


/**
 * Gets meta information for the current post author
 *
 * @param string $class
 * @return string $author_link
 */
if ( ! function_exists( '\theme\get_post_author_link' ) ) {
	function get_post_author_link( $class = 'author' ) {
		$class = esc_attr( $class );

		$author_link = "<span class=\"{$class} vcard\">";
		$author_link .= "<a href=\"" . esc_attr( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
		$author_link .= "\">" . get_the_author() . '</a></span>';

		return $author_link;
	}
}


/**
 * Gets the post categories
 *
 * @param string $class
 * @return string $categories_list
 */
if ( ! function_exists( '\theme\get_post_categories' ) ) {
	function get_post_categories( $class = 'nav-links' ) {
		$categories_list = get_the_category_list();

		if ( 'post' !== get_post_type() || ! $categories_list )
			return;

		$categories_list = str_replace(
			array( '<ul class="', '<li>', '<a' ),
			array( '<ul class="nav ', '<li class="nav-item">', '<a class="nav-link"' ),
			$categories_list
		);

		$class = esc_attr( $class );

		$categories_list = "<div class=\"{$class} cat-links\">\n{$categories_list}\n</div>\n";

		return $categories_list;
	}
}


/**
 * Gets the post tags
 *
 * @param string $class
 * @return string $tags_list
 */
if ( ! function_exists( '\theme\get_post_tags' ) ) {
	function get_post_tags( $class = 'nav-links row' ) {
		$tags_list = get_the_tag_list( "<ul>\n", '', "</ul>" );

		if ( 'post' !== get_post_type() || ! ( $tags_list && ! is_wp_error( $tags_list ) ) )
			return;

		$tags_list = str_replace(
			array( '<ul>', '<a' ),
			array( '<ul class="nav post-tags">', '<li class="nav-item"><a class="nav-link"' ),
			$tags_list
		);

		$class = esc_attr( $class );

		$tags_list = "<div class=\"{$class} tags-links\">\n{$tags_list}\n</div>\n";

		return $tags_list;
	}
}


/**
 * Gets the entry post click target
 *
 * @param string $class
 * @return string $click_target
 */
if ( ! function_exists( '\theme\get_the_entry_post_click_target' ) ) {
	function get_the_entry_post_click_target( $class = 'card-click-target entry-click-target' ) {
		$class = esc_attr( $class );

		$click_target = "<a href=\"" . esc_attr( get_the_permalink() ) . "\"";
		$click_target .=" class=\"{$class} click-target\"";
		$click_target .=  " aria-hidden=\"true\"></a>\n";

		return $click_target;
	}
}


/**
 * Displays the entry post click target
 *
 * @param string $class
 */
if ( ! function_exists( '\theme\the_entry_post_click_target' ) ) {
	function the_entry_post_click_target( $class = 'card-click-target entry-click-target' ) {
		echo \theme\get_the_entry_post_click_target( $class );
	}
}


/**
 * Gets the post meta informations: categories, published date, author and edit link 
 *
 * @return string $r
 */
if ( ! function_exists( '\theme\get_the_post_meta' ) ) {
	function get_the_post_meta() {
		$r = '';

		if ( $post_categories = \theme\get_post_categories() ) :
			$r .= "\t<div class=\"post-categories\">\n\t\t";
			$r .= "<span class=\"sr-only sr-only-focusable post-categories-label\">";
			$r .= __( 'Categories', 'theme' ) . "</span>\n\t\t{$post_categories}\t</div>\n";
		endif;

		$r .= "\t<div class=\"posted-on\">";

		if ( $post_time_link = \theme\get_post_time_link( 'post-time published' ) ) :
			$r .= "\n\t\t";
			$r .= sprintf(
				_x( '%1$s %2$s', 'post-time', 'theme' ),
				'<span class="post-time-label">' .
					_x( 'Posted on', 'post-time', 'theme' ) . '</span>',
				$post_time_link
			);
		endif;

		if ( $post_author_link = \theme\get_post_author_link( 'post-author author' ) ) :
			$r .= "\n\t\t<span class=\"byline\">";
			$r .= sprintf(
				_x( '%1$s %2$s', 'post-author', 'theme' ),
				'<span class="post-author-label">' .
					_x( 'by', 'post-author', 'theme' ) . '</span>',
				$post_author_link
			);
			$r .= "</span>";
		endif;

		if ( is_user_logged_in() ) :
			$r .= \theme\get_the_edit_link();
		endif;

		$r .= "\t</div>";

		return $r;
	}
}


/**
 * Displays the post meta informations: categories, published date, author and edit link 
 */
if ( ! function_exists( '\theme\the_post_meta' ) ) {
	function the_post_meta() {
		echo \theme\get_the_post_meta();
	}
}


/**
 * Gets the entry post meta informations: published date, author and edit link 
 *
 * @return string $r
 */
if ( ! function_exists( '\theme\get_the_entry_post_meta' ) ) {
	function get_the_entry_post_meta() {
		$r = '';

		if ( $post_time_link = \theme\get_post_time_link( 'entry-time published') ) :
			$r .= sprintf(
				_x( '%1$s %2$s', 'post-time', 'theme' ),
				'<span class="sr-only sr-only-focusable entry-time-label">' .
					_x( 'Posted on', 'post-time', 'theme' ) . '</span>',
				$post_time_link
			);
		endif;

		if ( $post_author_link = \theme\get_post_author_link( 'entry-author author' ) ) :
			$r .= sprintf(
				_x( '%1$s %2$s', 'post-author', 'theme' ),
				'<span class="sr-only sr-only-focusable entry-author-label">' .
					_x( 'by', 'post-author', 'theme' ) . '</span>',
				$post_author_link
			);
		endif;

		if ( is_user_logged_in() ) :
			$r .= \theme\get_the_edit_link( 'entry-edit-link' );
		endif;

		return $r;
	}
}


/**
 * Displays the entry post meta informations: published date, author and edit link 
 */
if ( ! function_exists( '\theme\the_entry_post_meta' ) ) {
	function the_entry_post_meta() {
		echo \theme\get_the_entry_post_meta();
	}
}


/**
 * Gets the post footer meta informations: tags 
 *
 * @return string $r
 */
if ( ! function_exists( '\theme\get_the_post_footer' ) ) {
	function get_the_post_footer() {
		$r = '';

		if ( $post_tags = \theme\get_post_tags() ) :
			$r .= "\t<div class=\"post-tags\">\n\t\t";
			$r .= "<span class=\"post-tags-label\">";
			$r .= __( 'Tags', 'theme' ) . "</span>\n\t\t{$post_tags}\t</div>\n";
		endif;

		return $r;
	}
}


/**
 * Displays the post footer meta informations: tags 
 */
if ( ! function_exists( '\theme\the_post_footer' ) ) {
	function the_post_footer() {
		echo \theme\get_the_post_footer();
	}
}


/**
 * Gets the entry post thumbnail
 *
 * @param object|mixed $post - \WP_Post
 * @param string $size
 * @param array|string $attr
 * @return string $r
 */
if ( ! function_exists( '\theme\get_the_entry_post_thumbnail' ) ) {
	function get_the_entry_post_thumbnail(
		$post = null,
		$size = 'post-thumbnail',
		$attr = array('class' => 'card-img-top')
	) {
		$r = "<div class=\"entry-thumbnail\">\n\t";
		$r .= "<a href=\"" . esc_attr( get_the_permalink() ) . "\">";
		$r .= get_the_post_thumbnail( $post, $size, $attr );
		$r .= "</a>\n</div>\n";

		return $r;
	}
}


/**
 * Displays the entry post thumbnail
 *
 * @param object|mixed $post - \WP_Post
 * @param string $size
 * @param array|string $attr
 */
if ( ! function_exists( '\theme\the_entry_post_thumbnail' ) ) {
	function the_entry_post_thumbnail(
		$post = null,
		$size = 'post-thumbnail',
		$attr = array('class' => 'card-img-top')
	) {
		echo \theme\get_the_entry_post_thumbnail( $post, $size, $attr );
	}
}


/**
 * Gets the entry post excerpt
 *
 * @param bool $filter
 * @return string $r
 */
if ( ! function_exists( '\theme\get_the_entry_post_excerpt' ) ) {
	function get_the_entry_post_excerpt( $filter = false ) {
		$r = "<div class=\"card-text entry-excerpt\">\n";

		/**
		 * Filter applyed to the_excerpt() function
		 *
		 * @param string $post_excerpt
		 */
		if ( $filter )
			$r .= apply_filters( 'the_excerpt', get_the_excerpt() );
		else
			$r .= get_the_excerpt();

		$r .= "\n</div>";

		return $r;
	}
}


/**
 * Displays the entry post excerpt
 *
 * @param bool $filter
 */
if ( ! function_exists( '\theme\the_entry_post_excerpt' ) ) {
	function the_entry_post_excerpt( $filter = true ) {
		echo \theme\get_the_entry_post_excerpt( $filter );
	}
}


/**
 * Gets the entry post format
 *
 * @param string|bool $post_format
 * @param string $post_format_data
 * @return string $r
 */
if ( ! function_exists( '\theme\get_the_entry_post_format' ) ) {
	function get_the_entry_post_format( $post_format, $post_format_data = '' ) {
		if ( ! $post_format )
			$post_format = get_post_format();

		if ( ! $post_format_data )
			$post_format_data = \theme\get_post_format_data( $post_format );

		/**
		 * Filter to render the item responsive
		 *
		 * @param string $html
		 */
		$post_format_data = apply_filters( 'theme_embed_responsive', $post_format_data );
		$r = "<div class=\"entry-object entry-{$post_format}\">\n{$post_format_data}\n</div>\n";

		return $r;
	}
}


/**
 * Displays the entry post format
 *
 * @param string|bool $post_format
 * @param string $post_format_data
 */
if ( ! function_exists( '\theme\the_entry_post_format' ) ) {
	function the_entry_post_format( $post_format, $post_format_data = '' ) {
		echo \theme\get_the_entry_post_format( $post_format, $post_format_data = '' );
	}
}
