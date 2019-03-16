<?php
/**
 * theme abstraction layer for “acf pro“
 *
 * //TODO implement acf light
 * //TODO warn acf light
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


/**
 * Abstraction layer class for “acf pro“
 */
abstract class Layer_ACFPRO {


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
		return \get_fields( $post_id, $format );
	}

	/**
	 * Gets a field by key name
	 *
	 * @access public
	 * @static
	 *
	 * @param string $key
	 * @param bool $single
	 * @param null|int|bool $post_id
	 * @param bool $format
	 * @return mixed void
	 */
	public static function get_field( $key, $single = false, $post_id = null, $format = true ) {
		if ( ! $single && is_int( \acf_get_loop( 'active', 'i' ) ) )
			return \get_sub_field( $key, $format );
		else
			return \get_field( $key, $post_id, $format );
	}

	/**
	 * Gets a sub-field by key name
	 *
	 * @access public
	 * @static
	 *
	 * @param string $key
	 * @param mixed $default
	 * @param bool $format
	 * @return mixed void
	 */
	public static function get_subfield( $key, $post_id = null, $format = true ) {
		return \get_sub_field( $key, $format );
	}

	/**
	 * Gets a field name
	 *
	 * @access public
	 * @static
	 *
	 * @param mixed $default
	 * @return mixed void
	 */
	public static function get_field_name() {
		return \get_row_layout();
	}

	/**
	 * Gets the current loop
	 *
	 * @access public
	 * @static
	 *
	 * @return null|array void
	 */
	public static function get_loop() {
		return \acf_get_loop( 'active' );
	}

	/**
	 * Gets the current loop index
	 *
	 * @access public
	 * @static
	 *
	 * @return int void
	 */
	public static function get_current_index() {
		return \acf_get_loop( 'active', 'i' );
	}

	/**
	 * Checks for rows and initializes the loop
	 *
	 * @access public
	 * @static
	 *
	 * @param string $key
	 * @param null|int $post_id
	 * @return object void
	 */
	public static function have_rows( $key, $post_id = null ) {
		return \have_rows( $key, $post_id );
	}

	/**
	 * Gets the current loop row
	 *
	 * @access public
	 * @static
	 *
	 * @param bool $format
	 * @return mixed void
	 */
	public static function get_row( $format = false ) {
		return \get_row( $format );
	}

	/**
	 * Access the current loop row
	 *
	 * @access public
	 * @static
	 *
	 * @param bool $format
	 * @return bool void
	 */
	public static function the_row( $format = false ) {
		return \the_row( $format );
	}


}
