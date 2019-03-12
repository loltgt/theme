<?php
/**
 * theme abstraction layer factory
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \theme\Theme;


/**
 * Abstraction layer class for “acf“
 */
abstract class Layer_Factory {


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
		return get_post_custom( $post_id );
	}

	/**
	 * Gets a field by key name
	 *
	 * @access public
	 * @static
	 *
	 * @param string $key
	 * @param null|int|bool $post_id
	 * @param bool $format
	 * @return mixed void
	 */
	public static function get_field( $key, $post_id = null, $format = true ) {
		global $post;

		if ( ! $post_id )
			$post_id = $post->ID ? get_post_ID() : 0;

		$field = ( $post_id === true );
		$post_id = $field ? 0 : $post_id;

		$field = null;

		if ( $field && self::get_loop() )
			$field = self::get_subfield( $key, true );
		else
			$field = get_post_meta( $post_id, $key, true );

		return $field;
	}

	/**
	 * Gets a sub-field by key name
	 *
	 * @access public
	 * @static
	 *
	 * @param string $key
	 * @param bool $format
	 * @return mixed void
	 */
	public static function get_subfield( $key, $format = true ) {
		if ( ( $current = self::get_loop() ) !== false )
			throw new Exception('not instanced');

		$subfield = null;

		$key = $current['key'];
		$post_id = $current['post_id'];

		if ( Theme::isset( "rows:{$key}:{$post_id}" ) ) {
			$rows = Theme::get( "rows:{$key}:{$post_id}" );

			if ( isset( $rows['fields'][$key] ) )
				$subfield = $rows['fields'][$key];
		}

		return $subfield;
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
		if ( ( $current = self::get_loop() ) !== false )
			throw new Exception('not instanced');

		$name = null;

		$key = $current['key'];
		$post_id = $current['post_id'];
		$i = self::get_current_index();

		if ( Theme::isset( "rows:{$key}:{$post_id}" ) ) {
			$rows = Theme::get( "rows:{$key}:{$post_id}" );

			if ( isset( $rows['indexes'][$i] ) )
				$name = $rows['indexes'][$i];
		}

		return $name;
	}

	/**
	 * Gets the current loop, public method
	 *
	 * @access public
	 * @static
	 *
	 * @return null|array void
	 */
	public static function get_loop() {
		return self::loop();
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
		return Theme::get( "current:rows:index", 0 );
	}

	/**
	 * Sets the current loop index
	 *
	 * @access private
	 * @static
	 *
	 * @param int $index
	 * @return bool void
	 */
	private static function set_current_index( $index ) {
		return Theme::set( "current:rows:index", (int) $index );
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
		global $post;

		if ( ! $post_id )
			$post_id = $post->ID ? get_post_ID() : 0;

		if ( Theme::isset( "rows:{$key}:{$post_id}" ) ) {
			$count = Theme::get( "count:rows:{$key}:{$post_id}", 0 );
		} else {
			$rows = self::get_field( $key, $post_id, false );
			$count = count($rows);

			if ( ! $count || $count == 1 )
				return false;

			$rows = array(
				'fields' => $rows,
				'indexes' => array_keys( $rows['fields'] )
			);

			$current = array( 'key' => $key, 'post_id' => $post_id );

			Theme::set( "rows:{$key}:{$post_id}", $rows );
			Theme::set( "current:rows", $current );
			Theme::set( "count:rows:{$key}:{$post_id}", $count );
		}

		if ( ! $count )
			return false;

		$i = self::get_current_index();
		$i++;

		if ( $i < $count )
			return true;
		else if ( $count && $i === $count )
			self::reset();

		return false;
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
		return self::next( true );
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
		return self::next() ? true : false;
	}

	/**
	 * Gets the current loop
	 *
	 * @access private
	 * @static
	 *
	 * @return null|array void
	 */
	private static function loop() {
		return Theme::get( "current:rows", false );
	}

	/**
	 * Steps to previous row in the current loop
	 *
	 * @access private
	 * @static
	 *
	 * @return bool $return
	 * @return bool void
	 */
	private static function previous( $return = false ) {
		if ( ( $current = self::get_loop() ) !== false )
			throw new Exception('not instanced');

		$key = $current['key'];
		$post_id = $current['post_id'];

		$rows = Theme::get( "rows:{$key}:{$post_id}" );

		$i = self::get_current_index();
		$i--;

		if ( isset( $rows['indexes'][$i] ) ) {
			self::set_current_index( $i );

			return $return ? $rows['indexes'][$i] : true;
		}

		return false;
	}

	/**
	 * Steps to next row in the current loop
	 *
	 * @access private
	 * @static
	 *
	 * @return bool $return
	 * @return bool void
	 */
	private static function next( $return = false ) {
		if ( ( $current = self::get_loop() ) !== false )
			throw new Exception('not instanced');

		$key = $current['key'];
		$post_id = $current['post_id'];

		$rows = Theme::get( "rows:{$key}:{$post_id}" );

		$i = self::get_current_index();
		$i++;

		if ( isset( $rows['indexes'][$i] ) ) {
			self::set_current_index( $i );

			return $return ? $rows['indexes'][$i] : true;
		}

		return false;
	}

	/**
	 * Reset loop and his parameters
	 *
	 * @access private
	 * @static
	 */
	private static function reset() {
		if ( ! self::loop() )
			throw new Exception('not instanced');

		Theme::unset( "current:rows" );
		Theme::unset( "current:rows:index" );
	}


}
