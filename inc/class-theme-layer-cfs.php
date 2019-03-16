<?php
/**
 * theme abstraction layer for “cfs“
 *
 * //TODO test
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Layer_Factory;


/**
 * Abstraction layer class for “cfs“
 */
abstract class Layer_CFS extends Layer_Factory {


	/**
	 * Gets all fields
	 *
	 * @access public
	 * @static
	 *
	 * @param null|int $post_id
	 * @param bool $format
	 * @return mixed void
	 */
	public static function get_fields( $post_id = null, $format = true ) {
		return \CFS()->get_fields( $post_id, array('format' => ( $format ? 'api' : 'raw' ) ) );
	}

	/**
	 * Gets a field by key name
	 *
	 * @access public
	 * @static
	 *
	 * @param string $key
	 * @param bool $single
	 * @param null|int $post_id
	 * @param bool $format
	 * @return mixed void
	 */
	public static function get_field( $key, $post_id = null, $format = true ) {
		if ( ! $single && self::loop() ) {
			return self::get_subfield( $key, array('format' => ( $format ? 'api' : 'raw' ) ) );
		} else {
			return \CFS()->get_field( $key, $post_id, array('format' => ( $format ? 'api' : 'raw' ) ) );
		}
	}


}
