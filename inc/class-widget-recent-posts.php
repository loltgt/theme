<?php
/**
 * Widget_Recent_Posts class
 *
 * from Widget API: \WP_Widget_Recent_Posts class
 *
 * @see \WP_Widget_Recent_Posts
 *
 * @package theme
 * @subpackage Widgets
 * @version 1.0.0
 */

namespace theme;

use \WP_Widget_Recent_Posts;
use \WP_Query;


/**
 * Core class used to implement a Recent Posts widget
 *
 * @see WP_Widget
 */
class Widget_Recent_Posts extends WP_Widget_Recent_Posts {

	/**
	 * Sets up a new Recent Posts widget instance
	 */
	function __construct() {
		$widget_ops = array(
			'classname' => 'widget_recent_entries',
			'description' => __( 'Your site&#8217;s most recent Posts.' ),
			'customize_selective_refresh' => true,
		);

		parent::__construct( 'recent-posts', __( 'Recent Posts' ), $widget_ops );
		$this->alt_option_name = 'widget_recent_entries';
	}


	/**
	 * Output content for the current Recent Posts widget instance
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		$args['title'] = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );

		// This filter is documented in wp-includes/widgets/class-wp-widget-pages.php
		$args['title'] = apply_filters( 'widget_title', $args['title'], $instance, $this->id_base );

		$args['number'] = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;

		if ( ! $args['number'] )
			$args['number'] = 5;

		$args['show_date'] = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		/**
		 * Filters the arguments for the Recent Posts widget
		 *
		 * @see \WP_Query>get_posts()
		 *
		 * @param array void - ‘args‘
		 * @param array $instance
		 */
		$args['posts'] = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page' => $args['number'],
			'no_found_rows' => true,
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
			//TODO move in filter
			'post__in' => get_option( 'sticky_posts' )
		), $instance ) );

		if ( ! $args['posts']->have_posts() )
			return;

		set_query_var( 'widget_args', $args );

		get_template_part( 'template-parts/widget-recent-posts' );

		set_query_var( 'widget_args', null );
	}

}
