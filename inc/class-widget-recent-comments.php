<?php
/**
 * Widget_Recent_Comments class
 *
 * from Widget API: \WP_Widget_Recent_Comments class
 *
 * @see \WP_Widget_Recent_Comments
 *
 * @package theme
 * @subpackage Widgets
 * @version 1.0.0
 */

namespace theme;

use \WP_Widget_Recent_Comments;


/**
 * Core class used to implement a Recent Comments widget
 *
 * @see WP_Widget
 */
class Widget_Recent_Comments extends WP_Widget_Recent_Comments {

	/**
	 * Sets up a new Recent Comments widget instance.
	 *
	 * @since 2.8.0
	 */
	function __construct() {
		$widget_ops = array(
			'classname' => 'widget_recent_comments',
			'description' => __( 'Your site&#8217;s most recent comments.' ),
			'customize_selective_refresh' => true
		);

		parent::__construct( 'recent-comments', __( 'Recent Comments' ), $widget_ops );
		$this->alt_option_name = 'widget_recent_comments';

		if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() )
			add_action( 'wp_head', array( $this, 'recent_comments_style' ) );
	}


	/**
	 * Outputs the content for the current Recent Comments widget instance
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		$args['title'] = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Comments' );

		// This filter is documented in wp-includes/widgets/class-wp-widget-pages.php
		$args['title'] = apply_filters( 'widget_title', $args['title'], $instance, $this->id_base );

		$args['number'] = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;

		if ( ! $args['number'] )
			$args['number'] = 5;

		/**
		 * Filters the arguments for the Recent Comments widget
		 *
		 * @see \WP_Comment_Query->query()
		 *
		 * @param array $comment_args
		 * @param array $instance
		 */
		$args['comments'] = get_comments( apply_filters( 'widget_comments_args', array(
			'number' => $args['number'],
			'status' => 'approve',
			'post_status' => 'publish'
		), $instance ) );

		if ( $args['comments'] && is_array( $args['comments'] ) ) {
			// Prime cache for associated posts. (Prime post term cache if we need it for permalinks.)
			$post_ids = array_unique( wp_list_pluck( $args['comments'], 'comment_post_ID' ) );
			_prime_post_caches( $post_ids, strpos( get_option( 'permalink_structure' ), '%category%' ), false );
		} else {
			return;
		}

		set_query_var( 'widget_args', $args );

		get_template_part( 'template-parts/widget-recent-comments' );

		set_query_var( 'widget_args', null );
	}

}
