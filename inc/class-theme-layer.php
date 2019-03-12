<?php
/**
 * theme abstraction layer
 *
 * @package theme
 * @version 1.0
 */

namespace theme;

use \Exception;


/**
 * Abstraction layer class
 */
class Layer {


	/**
	 * Function __construct
	 */
	function __construct() {
		self::autoload_layer();
	}


	/**
	 * Shorthand method to access the current layer class
	 *
	 * @access private
	 * @static
	 *
	 * @return object void
	 */
	private static function _layer() {
		return self::get_layer();
	}

	/**
	 * Gets the current layer class
	 *
	 * @access public
	 * @static
	 *
	 * @return string void
	 */
	public static function get_layer() {
		if ( ! defined( "THEME_LAYER_{COOKIEHASH}" ) )
			throw new Exception( '/theme/Layer::get_layer() : Not correctly initialized.' );

		return constant( "THEME_LAYER_{COOKIEHASH}" );
	}

	/**
	 * Sets the current layer class
	 *
	 * @access public
	 * @static
	 *
	 * @param object $layer
	 * @return string void
	 */
	public static function set_layer( $layer ) {
		if ( defined( "THEME_LAYER_{COOKIEHASH}" ) )
			throw new Exception( 'Cannot run \theme\Layer twice.' );

		$_layer = "\\" . __NAMESPACE__ . "\\Layer_{$layer}";

		if ( ! class_exists( $_layer ) )
			throw new Exception( '/theme/Layer::set_layer() : Class not exists.' );

		return define( "THEME_LAYER_{COOKIEHASH}", $_layer );
	}

	/**
	 * Sets the current layer class
	 *
	 * //TODO implement
	 *
	 * @access public
	 * @static
	 */
	public static function autoload_layer() {
		require_once get_theme_file_path( '/acfpro-local-field-groups.php' );

		require_once TEMPLATE_DIRECTORY . INCLUDES_BASE_PATH . '/class-theme-layer-acfpro.php';

		self::set_layer( 'ACFPRO' );
	}

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
		return self::_layer()::get_fields( $post_id, $format );
	}

	/**
	 * Gets a field by key name
	 *
	 * @access public
	 * @static
	 *
	 * @param string $key
	 * @param mixed $default
	 * @param null|int $post_id
	 * @param bool $format
	 * @return mixed void
	 */
	public static function get_field( $key, $default = null, $post_id = null, $format = true ) {
		$single = ( $post_id === true );
		$post_id = $single ? 0 : $post_id;

		if ( $field = self::_layer()::get_field( $key, $single, $post_id, $format ) )
			return $field;

		return $default;
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
	public static function get_subfield( $key, $default = null, $format = true ) {
		if ( $subfield = self::_layer()::get_subfield( $key, $format ) )
			return $subfield;

		return $default;
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
	public static function get_field_name( $default = null ) {
		if ( $name = self::_layer()::get_field_name() )
			return $name;

		return $default;
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
		return self::_layer()::loop();
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
		return self::_layer()::get_current_index();
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
		return self::_layer()::have_rows( $key, $post_id );
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
		return self::_layer()::get_row( $format );
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
		return self::_layer()::the_row( $format );
	}


}

new Layer;